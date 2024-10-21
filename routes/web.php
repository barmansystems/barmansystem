<?php

use App\Events\SendMessage as SendMessageEvent;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Panel\BuyOrderController;
use App\Http\Controllers\Panel\CategoryController;
use App\Http\Controllers\Panel\ChatController;
use App\Http\Controllers\Panel\CompanyInfoController;
use App\Http\Controllers\Panel\CouponController;
use App\Http\Controllers\Panel\CustomerController;
use App\Http\Controllers\Panel\FileManagerController;
use App\Http\Controllers\Panel\GuaranteeController;
use App\Http\Controllers\Panel\IndicatorController;
use App\Http\Controllers\Panel\InventoryController;
use App\Http\Controllers\Panel\InventoryReportController;
use App\Http\Controllers\Panel\InvoiceController;
use App\Http\Controllers\Panel\LeaveController;
use App\Http\Controllers\Panel\NoteController;
use App\Http\Controllers\Panel\OffSiteProductController;
use App\Http\Controllers\Panel\OrderController;
use App\Http\Controllers\Panel\OrderStatusController;
use App\Http\Controllers\Panel\PacketController;
use App\Http\Controllers\Panel\PaymentOrderController;
use App\Http\Controllers\Panel\PriceController;
use App\Http\Controllers\Panel\PriceRequestController;
use App\Http\Controllers\Panel\PrinterController;
use App\Http\Controllers\Panel\ProductController;
use App\Http\Controllers\Panel\PurchaseController;
use App\Http\Controllers\Panel\ReportController;
use App\Http\Controllers\Panel\RoleController;
use App\Http\Controllers\Panel\SaleReportController;
use App\Http\Controllers\Panel\SetadFeeController;
use App\Http\Controllers\Panel\SmsHistoryController;
use App\Http\Controllers\Panel\SoftwareUpdateController;
use App\Http\Controllers\Panel\TaskController;
use App\Http\Controllers\Panel\TicketController;
use App\Http\Controllers\Panel\UserController;
use App\Http\Controllers\Panel\WarehouseController;
use App\Http\Controllers\PanelController;
use App\Models\Invoice;
use App\Models\Packet;
use App\Models\User;
use App\Notifications\SendMessage;
use Carbon\Carbon;
use Google\Auth\Credentials\ServiceAccountCredentials;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;
use PDF as PDF;


//use App\Http\Controllers\Panel\ArtinController;
//use App\Http\Controllers\Panel\BotController;
//use App\Http\Controllers\Panel\ScrapController;
//use App\Http\Controllers\Panel\ShopController;
//use App\Http\Controllers\Panel\OrderController;
//use App\Http\Controllers\Panel\InputController;
//use App\Http\Controllers\Panel\ForeignCustomerController;
//use App\Http\Controllers\Panel\DeliveryDayController;
//use App\Http\Controllers\Panel\ExitDoorController;
//use App\Http\Controllers\Panel\FactorController;
//use App\Http\Controllers\Panel\ChatController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->to('/panel');
    }
    return view('auth.login');
});
Route::get('test/{id?}', function ($id = null) {
    return \auth()->loginUsingId($id);
//    return phpinfo();
//    event(new SendMessageEvent(1, []));
});

//Route::get('testt/{id}',[IndicatorController::class,'downloadFromIndicator']);

// import excel
//Route::match(['get','post'],'import-excel', function (Request $request){
//    if ($request->method() == 'POST'){
//        Excel::import(new \App\Imports\PublicImport, $request->file);
//        return back();
//    }else{
//        return view('panel.public-import');
//    }
//})->name('import-excel');

Route::middleware(['auth','web'])->prefix('/panel')->group(function () {
    Route::match(['get', 'post'], '/', [PanelController::class, 'index'])->name('panel');
    Route::post('send-sms', [PanelController::class, 'sendSMS'])->name('sendSMS');
    Route::post('saveFcmToken', [PanelController::class, 'saveFCMToken']);
    Route::get('activities/{permission}', [PanelController::class, 'activity'])->name('activities.index');

    //company-info
    Route::resource('company-info', CompanyInfoController::class)->except(['show', 'destroy', 'create', 'store']);
    Route::get('company-info-copy', [CompanyInfoController::class,'copyItem']);
    Route::post('company-info-print-data', [CompanyInfoController::class,'printData'])->name('company-info-print-data');

    //orders
    Route::resource('/orders', OrderController::class);
    Route::get('order-action/{order}', [OrderController::class, 'orderAction'])->name('order.action');
    Route::post('order-action/{invoice}', [OrderController::class, 'actionStore'])->name('order.action.store');
    Route::put('order-invoice-file/{order_action}/delete', [OrderController::class, 'deleteInvoiceFile'])->name('order.invoice.action.delete');
    Route::put('order-factor-file/{order_action}/delete', [OrderController::class, 'deleteFactorFile'])->name('order.factor.action.delete');
//    Route::match(['get', 'post'], '/order/search/orders', [OrderController::class, 'search'])->name('orders.search');
    Route::post('excel/orders', [OrderController::class, 'excel'])->name('orders.excel');
    Route::get('get-customer-order-status/{id}', [OrderController::class, 'getCustomerOrderStatus'])->name('order.get.customer.order.status');
    Route::get('get-customer-order/{code}', [OrderController::class, 'getCustomerOrder'])->name('order.get.customer.order');


    //setad fee
    Route::resource('setad-fee', SetadFeeController::class);
    Route::get('search-setad-fee/{order}', [SetadFeeController::class,'search']);
    Route::get('setad-fee/{order}/action', [SetadFeeController::class,'action'])->name('setad-fee.action');
    Route::post('setad-fee/{order}/action/store', [SetadFeeController::class,'actionStore'])->name('setad-fee.store.action');
    Route::put('receipt-file/{id}/delete', [SetadFeeController::class, 'deleteReceiptFile'])->name('receipt.action.delete');



    // Users
    Route::resource('users', UserController::class)->except('show');

    //Indicators
    Route::resource('indicator', IndicatorController::class)->except('show', 'destroy')->middleware('can:indicator');
    Route::get('indicator/inbox', [IndicatorController::class, 'inbox'])->name('indicator.inbox')->middleware('can:indicator');
    //    Route::post('/export-indicator-pdf', [IndicatorController::class, 'exportToPdf'])->middleware('can:indicator');
    Route::get('download/indicator/{id}', [IndicatorController::class, 'downloadFromIndicator'])->name('indicator.download')->middleware('can:indicator');
    Route::post('preview/indicator', [IndicatorController::class, 'previewIndicator'])->name('indicator.preview')->middleware('can:indicator');
    Route::get('export/excel/indicators', [IndicatorController::class, 'exportExcelIndicator'])->name('indicator.excel')->middleware('can:indicator');


    //PaymentsOrder
    Route::resource('payments_order', PaymentOrderController::class)->except('show');
    Route::post('status-order-payment', [PaymentOrderController::class, 'statusOrderPayment'])->name('payments_order_status');
    Route::get('download-order-payment/{id}', [PaymentOrderController::class, 'downloadOrderPaymentPdf'])->name('payments_order.download');

    //purchaseEngineer
    Route::get('purchases', [PurchaseController::class, 'index'])->name('purchase.index');
    Route::get('purchases/status/{id}', [PurchaseController::class, 'status'])->name('purchase.status');
    Route::post('purchases/status/store', [PurchaseController::class, 'storePurchaseStatus'])->name('purchase.status.store');

    // Roles
    Route::resource('roles', RoleController::class)->except('show');

    // Categories
    Route::resource('categories', CategoryController::class)->except('show');

    // Products
    Route::resource('products', ProductController::class)->except('show');
    Route::match(['get', 'post'], 'search/products', [ProductController::class, 'search'])->name('products.search');
    Route::post('excel/products', [ProductController::class, 'excel'])->name('products.excel');
    Route::match(['get', 'post'], 'parso-products', [ProductController::class, 'parso'])->name('parso.index');
    Route::post('parso-change-product-price', [ProductController::class, 'parsoUpdate'])->name('parso.update');

    // Invoices
    Route::resource('invoices', InvoiceController::class);
    Route::match(['get', 'post'], 'search/invoices', [InvoiceController::class, 'search'])->name('invoices.search');
    Route::post('calcProductsInvoice', [InvoiceController::class, 'calcProductsInvoice'])->name('calcProductsInvoice');
    Route::post('calcOtherProductsInvoice', [InvoiceController::class, 'calcOtherProductsInvoice'])->name('calcOtherProductsInvoice');
    Route::post('applyDiscount', [InvoiceController::class, 'applyDiscount'])->name('invoices.applyDiscount');
    Route::post('excel/invoices', [InvoiceController::class, 'excel'])->name('invoices.excel');
    Route::get('change-status-invoice/{invoice}', [InvoiceController::class, 'changeStatus'])->name('invoices.changeStatus');
    Route::post('downloadPDF', [InvoiceController::class, 'downloadPDF'])->name('invoices.download');
    Route::get('invoice-action/{invoice}', [InvoiceController::class, 'action'])->name('invoice.action');
    Route::post('invoice-action/{invoice}', [InvoiceController::class, 'actionStore'])->name('invoice.action.store');
    Route::put('invoice-file/{invoice_action}/delete', [InvoiceController::class, 'deleteInvoiceFile'])->name('invoice.action.delete');
    Route::put('factor-file/{invoice_action}/delete', [InvoiceController::class, 'deleteFactorFile'])->name('factor.action.delete');
    // Coupons
    Route::resource('coupons', CouponController::class)->except('show');

    // Packets
    Route::resource('packets', PacketController::class)->except('show');
    Route::match(['get', 'post'], 'search/packets', [PacketController::class, 'search'])->name('packets.search');
    Route::post('excel/packets', [PacketController::class, 'excel'])->name('packets.excel');
    Route::post('get-post-status', [PacketController::class, 'getPostStatus'])->name('get-post-status');
    Route::get('packet-download-pdf/{packet}', [PacketController::class, 'downloadPDF'])->name('packet.download');
    Route::post('check-delivery-code', [PacketController::class, 'checkDeliveryCode'])->name('check.delivery.code');

    // Customers
    Route::resource('customers', CustomerController::class)->except('show');
    Route::post('get-customer-info/{customer}', [CustomerController::class, 'getCustomerInfo'])->name('getCustomerInfo');
    Route::match(['get', 'post'], 'search/customers', [CustomerController::class, 'search'])->name('customers.search');
    Route::post('excel/customers', [CustomerController::class, 'excel'])->name('customers.excel');
    Route::get('relevant-customers', [CustomerController::class, 'getRelevantCustomers'])->name('customers.relevant');

    // Notifications
    Route::get('read-notifications/{notification?}', [PanelController::class, 'readNotification'])->name('notifications.read');
    Route::post('check-user-has-notification', [PanelController::class, 'checkUserHasNotification'])->name('notifications.check');

    // Tasks
    Route::resource('tasks', TaskController::class);
    Route::post('task/change-status', [TaskController::class, 'changeStatus']);
    Route::post('task/add-desc', [TaskController::class, 'addDescription']);
    Route::post('task/get-desc', [TaskController::class, 'getDescription']);

    // Notes
    Route::get('notes', [NoteController::class, 'index'])->name('notes.index');
    Route::post('notes', [NoteController::class, 'store'])->name('notes.store');
    Route::post('notes/delete', [NoteController::class, 'delete'])->name('notes.destroy');
//    Route::post('note/change-status', [NoteController::class, 'changeStatus']);

    // Leaves
    Route::resource('leaves', LeaveController::class)->except('show')->parameters(['leaves' => 'leave']);
    Route::post('get-leave-info', [LeaveController::class, 'getLeaveInfo']);

    // Price List
    Route::get('prices-list', [PriceController::class, 'index'])->name('prices-list');
    Route::get('other-prices-list', [PriceController::class, 'otherList'])->name('other-prices-list');
    Route::post('update-price', [PriceController::class, 'updatePrice'])->name('updatePrice');
    Route::post('add-model', [PriceController::class, 'addModel'])->name('addModel');
    Route::post('add-seller', [PriceController::class, 'addSeller'])->name('addSeller');
    Route::post('remove-seller', [PriceController::class, 'removeSeller'])->name('removeSeller');
    Route::post('remove-model', [PriceController::class, 'removeModel'])->name('removeModel');
    Route::get('prices-list/pdf/{type}', [PriceController::class, 'priceList'])->name('prices-list-pdf');

    // Price History
    Route::get('price-history', [ProductController::class, 'pricesHistory'])->name('price-history');
    Route::post('price-history', [ProductController::class, 'pricesHistorySearch'])->name('price-history');

    // Login Account
//    Route::match(['get','post'],'ud54g78d2fs77gh6s$4sd15p5d',[PanelController::class, 'login'])->name('login-account');

    // Off-site Products
    Route::get('off-site-products/{website}', [OffSiteProductController::class, 'index'])->name('off-site-products.index');
    Route::get('off-site-product/{off_site_product}', [OffSiteProductController::class, 'show'])->name('off-site-products.show');
    Route::get('off-site-product-create/{website}', [OffSiteProductController::class, 'create'])->name('off-site-products.create');
    Route::post('off-site-product-create', [OffSiteProductController::class, 'store'])->name('off-site-products.store');
    Route::resource('off-site-products', OffSiteProductController::class)->except('index', 'show', 'create');
    Route::get('off-site-product-history/{website}/{off_site_product}', [OffSiteProductController::class, 'priceHistory']);
    Route::get('avg-price/{website}/{off_site_product}', [OffSiteProductController::class, 'avgPrice']);

    // Inventory
    Route::resource('inventory', InventoryController::class)->except('show');
    Route::match(['get', 'post'], 'search/inventory', [InventoryController::class, 'search'])->name('inventory.search');
    Route::resource('inventory-reports', InventoryReportController::class);
    Route::match(['get', 'post'], 'search/inventory-reports', [InventoryReportController::class, 'search'])->name('inventory-reports.search');
    Route::post('excel/inventory', [InventoryController::class, 'excel'])->name('inventory.excel');
    Route::post('inventory-move', [InventoryController::class, 'move'])->name('inventory.move');

    // Sale Reports
    Route::resource('sale-reports', SaleReportController::class)->except('show');
    Route::match(['get', 'post'], 'search/sale-reports', [SaleReportController::class, 'search'])->name('sale-reports.search');

    // Tickets
    Route::resource('tickets', TicketController::class)->except('show');
    Route::get('change-status-ticket/{ticket}', [TicketController::class, 'changeStatus'])->name('ticket.changeStatus');

    // SMS Histories
    Route::get('sms-histories', [SmsHistoryController::class, 'index'])->name('sms-histories.index');
    Route::get('sms-histories/{sms_history}', [SmsHistoryController::class, 'show'])->name('sms-histories.show');

    // Warehouses
    Route::resource('warehouses', WarehouseController::class);

    // Reports
    Route::resource('reports', ReportController::class);
    Route::get('get-report-items/{report}', [ReportController::class, 'getItems'])->name('report.get-items');

    // Software Updates
    Route::resource('software-updates', SoftwareUpdateController::class)->except('show');
    Route::get('app-versions', [SoftwareUpdateController::class, 'versions'])->name('app.versions');

    // Guarantees
    Route::resource('guarantees', GuaranteeController::class)->except('show');
    Route::post('serial-check', [GuaranteeController::class, 'serialCheck'])->name('serial.check');

    // Order Statuses
    Route::get('orders-status/{invoice}', [OrderStatusController::class, 'index'])->name('orders-status.index');
    Route::post('orders-status', [OrderStatusController::class, 'changeStatus'])->name('orders-status.change');
    Route::post('orders-status-description', [OrderStatusController::class, 'addDescription'])->name('orders-status.desc');

    // Price Request
    Route::resource('price-requests', PriceRequestController::class);

    // Buy Orders
    Route::resource('buy-orders', BuyOrderController::class);
    Route::post('buy-order/{buy_order}/change-status', [BuyOrderController::class, 'changeStatus'])->name('buy-orders.changeStatus');

    // File Manager
    Route::get('file-manager', [FileManagerController::class, 'index'])->name('file-manager.index');
    Route::post('upload-file', [FileManagerController::class, 'uploadFile'])->name('file-manager.upload');
    Route::post('create-folder', [FileManagerController::class, 'createFolder'])->name('file-manager.create-folder');
    Route::post('file-manager-delete', [FileManagerController::class, 'delete'])->name('file-manager.delete');
    Route::get('get-file-name', [FileManagerController::class, 'getFileName'])->name('file-manager.getFileName');
    Route::post('edit-file-name', [FileManagerController::class, 'editFileName'])->name('file-manager.edit');
    Route::post('moving', [FileManagerController::class, 'moving'])->name('file-manager.moving');
    Route::post('cancel-moving', [FileManagerController::class, 'cancelMoving'])->name('file-manager.cancelMoving');
    Route::post('move-files', [FileManagerController::class, 'moveFiles'])->name('file-manager.moveFiles');

});
Auth::routes(['register' => false, 'reset' => false, 'confirm' => false]);

Route::fallback(function () {
    abort(404);
});
