<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePurchaseRequest;
use App\Models\Purchase;
use App\Models\User;
use App\Notifications\SendMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class PurchaseController extends Controller
{
    public function index()
    {
        $this->authorize('purchase-engineering');

        $purchases = Purchase::latest()->paginate(30);
        return view('panel.purchase.index', compact(['purchases']));
    }

    public function status($id)
    {
        $this->authorize('purchase-engineering');

        $purchase = Purchase::whereId($id)->firstOrFail();
        return view('panel.purchase.status', compact(['purchase']));
    }

    public function storePurchaseStatus(StorePurchaseRequest $request)
    {
        $this->authorize('purchase-engineering');

        $purchase = Purchase::whereId($request->purchase_id)->firstOrFail();
        $purchase->update([
            'status' => $request->status,
            'desc' => $request->desc,
            'count' => $request->count,
        ]);
        alert()->success('ثبت شد','موفقیت آمیز');
        if  ($purchase->status == 'purchase_done' ){
            $message = "کالای  ".$purchase->inventory->title." خریداری شد.";
            $users = User::whereHas('role.permissions', function ($q) {
                $q->where('name', 'warehouse-keeper');
            })->get();
            Notification::send($users, new SendMessage($message, url('/panel/purchases')));
        }
        activity_log('purchase-status', __METHOD__, $request->all());
        return redirect()->route('purchase.index');
    }
}
