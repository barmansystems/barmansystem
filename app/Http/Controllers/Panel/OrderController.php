<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBuyOrderRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Customer;
use App\Models\CustomerOrderStatus;
use App\Models\Invoice;
use App\Models\InvoiceAction;
use App\Models\Order;
use App\Models\OrderAction;
use App\Models\Permission;
use App\Models\Product;
use App\Models\Province;
use App\Models\Role;
use App\Models\User;
use App\Notifications\SendMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{

    public function index()
    {
        $this->authorize('customer-order-list');

        $orders = Order::query();

        if ($code = request()->query('code')) {
            $orders->where('code', 'like', '%' . $code . '%');
        }

        if ($status1 = request()->query('status')) {
            $status = $status1 == 'all' ? ['pending', 'invoiced', 'orders'] : [$status1];
            $orders->whereIn('status', $status);
        }

        if ($customer = request()->query('customer_id')) {
            $customers = Customer::pluck('id');
            $customers_id = $customer == 'all' ? $customers : [$customer];
            $orders->whereIn('customer_id', $customers_id);
        }

        if (auth()->user()->isAdmin() || auth()->user()->isAccountant() || auth()->user()->isCEO()) {
            $orders = $orders->latest()->paginate(30);
        } else {
            $orders = $orders->where('user_id', auth()->id())->latest()->paginate(30);
        }

        $customers = Customer::all(['id', 'name']);
        $permissionsId = Permission::whereIn('name', ['partner-tehran-user', 'partner-other-user', 'system-user', 'single-price-user'])->pluck('id');

        $roles_id = Role::whereHas('permissions', function ($q) use ($permissionsId) {
            $q->whereIn('permission_id', $permissionsId);
        })->pluck('id');

        return view('panel.orders.index', compact(['orders', 'customers', 'roles_id']));
    }


    public function create()
    {
        $this->authorize('customer-order-create');

        return view('panel.orders.create');
    }


    public function store(StoreOrderRequest $request)
    {
        $this->authorize('customer-order-create');
        $customer = Customer::whereId($request->buyer_name)->first();
//        dd($customer);


        $invoiceData = $this->sortData($request);
        $order = new Order();
        $order->description = $request->description;
        $order->type = $customer->customer_type;
        $order->req_for = $request->req_for;
        $order->code = $this->generateCode();
        $order->user_id = auth()->id();
        $order->customer_id = $request->buyer_name;
        $order->products = json_encode($invoiceData);
        $order->save();


        $this->send_notif_to_accountants($order);
        $this->send_notif_to_sales_manager($order);

        $order->order_status()->updateOrCreate(
            ['status' => 'register'],
            ['orders' => 1, 'status' => 'register']
        );

        activity_log('create-orders', __METHOD__, [$request->all(), $order]);
        alert()->success('سفارش مورد نظر با موفقیت ثبت شد', 'ثبت سفارش');
        return redirect()->route('orders.edit', $order->id);

    }


    public function show(Order $order)
    {
        return view('panel.orders.printable', compact(['order']));
    }


    public function edit(Order $order)
    {
        $this->authorize('customer-order-edit');

        // edit own invoice OR is admin
        $this->authorize('edit-order-customer', $order);
        if (auth()->user()->isAccountant()) {
            return back();
        }
        return view('panel.orders.edit', compact('order'));
    }


    public function update(Request $request, Order $order)
    {
        $this->authorize('customer-order-edit');

        // edit own invoice OR is admin
        $this->authorize('edit-order-customer', $order);


        $invoiceData = $this->sortData($request);
        $order->description = $request->description;
        $order->req_for = $request->req_for;
        $order->user_id = auth()->id();
        $order->customer_id = $request->buyer_name;
        $order->products = json_encode($invoiceData);
        $order->save();
        activity_log('edit-orders', __METHOD__, [$request->all(), $order]);
        alert()->success('سفارش مورد نظر با موفقیت ویرایش شد', 'ویرایش سفارش');
        return redirect()->route('orders.edit', $order->id);
    }


    public function destroy(Order $order)
    {
        $this->authorize('customer-order-delete');

        // log
        activity_log('delete-orders', __METHOD__, $order);

        $order->delete();
        return back();
    }

    private function sortData($request)
    {
        $products = [];
        if (isset($request->products) && is_array($request->products)) {
            foreach ($request->products as $index => $product) {
                $products[] = [
                    'products' => $product,
                    'colors' => $request->colors[$index],
                    'counts' => $request->counts[$index],
                    'units' => $request->units[$index],
                    'prices' => $request->prices[$index],
                    'total_prices' => $request->total_prices[$index],
                ];
            }
        }


        $other_products = [];
        if (isset($request->other_products) && is_array($request->other_products)) {
            foreach ($request->other_products as $index => $other_product) {
                $other_products[] = [
                    'other_products' => $other_product,
                    'other_colors' => $request->other_colors[$index],
                    'other_counts' => $request->other_counts[$index],
                    'other_units' => $request->other_units[$index],
                    'other_prices' => $request->other_prices[$index],
                    'other_total_prices' => $request->other_total_prices[$index],
                ];
            }
        }
        return [
            'products' => $products,
            'other_products' => $other_products,
        ];
    }


    public function orderAction(Order $order)
    {

        if (!Gate::allows('accountant') && $order->action == null) {
            return back();
        }

        if (!Gate::any(['sales-manager', 'accountant'])) {
            return back();
        }

        return view('panel.orders.action', compact('order'));
    }

    public function actionStore(Order $invoice, Request $request)
    {
//        dd("test");
        $status = $request->status;


        if ($request->has('send_to_accountant')) {
            if (!$request->has('confirm')) {
                alert()->error('لطفا تیک تایید پیش فاکتور را بزنید', 'عدم تایید');
                return back();
            }

            $invoice->action()->updateOrCreate([
                'order_id' => $invoice->id
            ], [
                'acceptor_id' => auth()->id(),
                'confirm' => 1
            ]);

            $title = 'ثبت و ارسال به حسابدار';
            $message = 'تاییدیه شما به حسابداری ارسال شد';

            //send notif to accountants
            $permissionsId = Permission::where('name', 'accountant')->pluck('id');
            $roles_id = Role::whereHas('permissions', function ($q) use ($permissionsId) {
                $q->whereIn('permission_id', $permissionsId);
            })->pluck('id');

            $url = route('order.action', $invoice->id);
            $notif_message = "پیش فاکتور سفارش {$invoice->customer->name} مورد تایید قرار گرفت";
            $accountants = User::whereIn('role_id', $roles_id)->get();
            Notification::send($accountants, new SendMessage($notif_message, $url));

            $invoice->order_status()->updateOrCreate(
                ['status' => 'awaiting_confirm_by_sales_manager'],
                ['orders' => 4, 'status' => 'awaiting_confirm_by_sales_manager']
            );
            //end send notif to accountants


        } elseif ($request->has('send_to_warehouse')) {
            $request->validate(['factor_file' => 'required|mimes:pdf|max:5000']);

            $file = upload_file_factor($request->factor_file, 'Action/Factors');

            $invoice->action()->updateOrCreate([
                'order_id' => $invoice->id
            ], [
                'factor_file' => $file,
                'sent_to_warehouse' => 1
            ]);

            $title = 'ثبت و ارسال به انبار';
            $message = 'فاکتور مورد نظر با موفقیت به انبار ارسال شد';

            $invoice->update(['status' => 'invoiced']);

            //send notif to warehouse-keeper and sales-manager
            $permissionsId = Permission::whereIn('name', ['warehouse-keeper', 'sales-manager'])->pluck('id');
            $roles_id = Role::whereHas('permissions', function ($q) use ($permissionsId) {
                $q->whereIn('permission_id', $permissionsId);
            })->pluck('id');

            $url = route('invoices.index');
            $notif_message = "فاکتور {$invoice->customer->name} دریافت شد";
            $accountants = User::whereIn('role_id', $roles_id)->get();
            Notification::send($accountants, new SendMessage($notif_message, $url));
            //end send notif to warehouse-keeper and sales-manager
        } else {

            if ($status == 'invoice') {
                $request->validate(['invoice_file' => 'required|mimes:pdf|max:5000']);


                $file = upload_file_factor($request->invoice_file, 'Action/Invoices');
                $invoice->action()->updateOrCreate([
                    'order_id' => $invoice->id
                ], [
                    'status' => $status,
                    'invoice_file' => $file
                ]);

                $title = 'ثبت و ارسال پیش فاکتور';
                $message = 'پیش فاکتور مورد نظر با موفقیت به همکار فروش ارسال شد';

                //send notif
                $roles_id = Role::whereHas('permissions', function ($q) {
                    $q->where('name', 'sales-manager');
                })->pluck('id');
                $sales_manager = User::where('id', '!=', auth()->id())->whereIn('role_id', $roles_id)->get();

                $url = route('order.action', $invoice->id);
                $notif_message = "پیش فاکتور {$invoice->customer->name} دریافت شد";
                Notification::send($invoice->user, new SendMessage($notif_message, $url));
                Notification::send($sales_manager, new SendMessage($notif_message, $url));
                //end send notif
            } else {
                $request->validate(['factor_file' => 'required|mimes:pdf|max:5000']);

                $file = upload_file_factor($request->factor_file, 'Action/Factors');
                $invoice->action()->updateOrCreate([
                    'order_id' => $invoice->id
                ], [
                    'status' => $status,
                    'factor_file' => $file,
                    'sent_to_warehouse' => 1
                ]);

                $title = 'ثبت و ارسال فاکتور';
                $message = 'فاکتور مورد نظر با موفقیت به انبار ارسال شد';

                //send notif to warehouse-keeper and sales-manager
                $permissionsId = Permission::whereIn('name', ['warehouse-keeper', 'sales-manager'])->pluck('id');
                $roles_id = Role::whereHas('permissions', function ($q) use ($permissionsId) {
                    $q->whereIn('permission_id', $permissionsId);
                })->pluck('id');

                $url = route('invoices.index');
                $notif_message = "فاکتور {$invoice->customer->name} دریافت شد";
                $accountants = User::whereIn('role_id', $roles_id)->get();
                Notification::send($accountants, new SendMessage($notif_message, $url));
                //end send notif to warehouse-keeper and sales-manager
            }

            $status = $status == 'invoice' ? 'pending' : 'invoiced';
            $invoice->update(['status' => $status]);
        }

        // log
        activity_log('order-action', __METHOD__, [$request->all(), $invoice]);
        $invoice->order_status()->updateOrCreate(
            ['status' => 'processing_by_accountant_step_1'],
            ['orders' => 2, 'status' => 'processing_by_accountant_step_1']
        );
        $invoice->order_status()->updateOrCreate(
            ['status' => 'pre_invoice'],
            ['orders' => 3, 'status' => 'pre_invoice']
        );
        alert()->success($message, $title);
        return back();
    }


    public function deleteInvoiceFile(OrderAction $orderAction)
    {

        $order = Order::whereId($orderAction->order_id)->first();
        activity_log('delete-invoice-file', __METHOD__, $orderAction);

        $order->order_status()->where('status', 'processing_by_accountant_step_1')->delete();
        $order->order_status()->where('status', 'pre_invoice')->delete();

        $order->update(['status' => 'orders']);
        unlink(public_path($orderAction->invoice_file));
        $orderAction->delete();

        alert()->success('فایل پیش فاکتور مورد نظر حذف شد', 'حذف پیش فاکتور');
        return back();
    }

    public function deleteFactorFile(OrderAction $orderAction)
    {
        // log
        activity_log('delete-factor-file', __METHOD__, $orderAction);

        unlink(public_path($orderAction->factor_file));

        $orderAction->update([
            'factor_file' => null,
            'sent_to_warehouse' => 0
        ]);

        if ($orderAction->status == 'factor') {
            $orderAction->delete();
        }

        alert()->success('فایل فاکتور مورد نظر حذف شد', 'حذف فاکتور');
        return back();
    }


    private function send_notif_to_accountants(Order $order)
    {
        $roles_id = Role::whereHas('permissions', function ($q) {
            $q->where('name', 'accountant');
        })->pluck('id');
        $accountants = User::where('id', '!=', auth()->id())->whereIn('role_id', $roles_id)->get();

        $url = route('invoices.edit', $order->id);
        $message = "سفارش '{$order->customer->name}' ثبت شد";

        Notification::send($accountants, new SendMessage($message, $url));
    }

    private function send_notif_to_sales_manager(Order $order)
    {
        $roles_id = Role::whereHas('permissions', function ($q) {
            $q->where('name', 'sales-manager');
        })->pluck('id');
        $managers = User::where('id', '!=', auth()->id())->whereIn('role_id', $roles_id)->get();

        $url = route('invoices.edit', $order->id);
        $message = "سفارش '{$order->customer->name}' ثبت شد";

        Notification::send($managers, new SendMessage($message, $url));
    }


    public function excel()
    {
        return Excel::download(new \App\Exports\OrderExport, 'orders.xlsx');
    }


    public function getCustomerOrderStatus($id)
    {
        $order = Order::with('order_status')->whereId($id)->first();

        if ($order->type == 'setad') {
            $statuses = CustomerOrderStatus::ORDER;
        } else {
            $statuses = CustomerOrderStatus::ORDER_OTHER;
        }

        $statusData = [];


        foreach ($statuses as $key => $status) {
            $date = optional($order->order_status()->where('status', $status)->first())->created_at ?
                verta($order->order_status()->where('status', $status)->first()->created_at)->format('H:i %Y/%m/%d') : '';

            $statusData[] = [
                'status' => $status,
                'status_label' => CustomerOrderStatus::STATUS[$status],
                'date' => $date,
                'pending' => false,
            ];
        }

        $lastDateIndex = -1;

        foreach ($statusData as $index => $statusItem) {
            if (!empty($statusItem['date'])) {
                $lastDateIndex = $index;
            }
        }
        if ($lastDateIndex !== -1 && $lastDateIndex + 1 < count($statusData)) {
            $statusData[$lastDateIndex + 1]['pending'] = true;
        }
        return response()->json($statusData);
    }


    public function generateCode()
    {
        $code = '666' . str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);

        while (Order::where('code', $code)->lockForUpdate()->exists()) {
            $code = '666' . str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
        }

        return $code;
    }


    public function getCustomerOrder($code)
    {
        $order = Order::where('code', $code)->first();

        $mergedProducts = [];

        if ($order) {
            $decodedProducts = json_decode($order->products);

            if (!empty($decodedProducts->products)) {
                $productIds = collect($decodedProducts->products)->pluck('products');
                $productsFromDB = Product::whereIn('id', $productIds)->get()->keyBy('id');

                foreach ($decodedProducts->products as $product) {
                    $productModel = $productsFromDB->get($product->products);
                    $mergedProducts[] = [
                        'title' => $productModel ? $productModel->title : 'Unknown Product',
                        'color' => Product::COLORS[$product->colors] ?? 'Unknown Color',
                        'count' => $product->counts,
                        'unit' => $product->units,
                        'price' => 0,
                    ];
                }
            }

            if (!empty($decodedProducts->other_products)) {
                foreach ($decodedProducts->other_products as $product) {
                    $mergedProducts[] = [
                        'title' => $product->other_products,
                        'color' => $product->other_colors,
                        'count' => $product->other_counts,
                        'unit' => $product->other_units,
                        'price' => 0,
                    ];
                }
            }

            $data = [
                'customer' => $order->customer,
                'order' => $mergedProducts
            ];
            $response = [
                'status' => 'success',
                'data' => $data
            ];
            return response()->json($response, 200);
        }
        $response = [
            'status' => 'failed',
            'data' => null
        ];
        return response()->json($response, 200);
    }

}
