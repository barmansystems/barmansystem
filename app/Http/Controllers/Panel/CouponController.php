<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCouponRequest;
use App\Http\Requests\UpdateCouponRequest;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $this->authorize('coupons-list');

        $coupons = Coupon::latest()->paginate(30);
        return view('panel.coupons.index', compact('coupons'));
    }

    public function create()
    {
        $this->authorize('coupons-create');

        return view('panel.coupons.create');
    }

    public function store(StoreCouponRequest $request)
    {
        $this->authorize('coupons-create');

        $coupon = Coupon::create([
            'title' => $request->title,
            'code' => $request->code,
            'amount_pc' => $request->amount_pc,
        ]);

        // log
        activity_log('create-coupon', __METHOD__, [$request->all(), $coupon]);

        alert()->success('کد تخفیف مورد نظر با موفقیت ایجاد شد','ایجاد کد تخفیف');
        return redirect()->route('coupons.index');
    }

    public function show(Coupon $coupon)
    {
        //
    }

    public function edit(Coupon $coupon)
    {
        $this->authorize('coupons-edit');

        return view('panel.coupons.edit', compact('coupon'));

    }

    public function update(UpdateCouponRequest $request, Coupon $coupon)
    {
        $this->authorize('coupons-edit');

        // log
        activity_log('edit-coupon', __METHOD__, [$request->all(), $coupon]);

        $coupon->update([
            'title' => $request->title,
            'code' => $request->code,
            'amount_pc' => $request->amount_pc,
        ]);

        alert()->success('کد تخفیف مورد نظر با موفقیت ویرایش شد','ویرایش کد تخفیف');
        return redirect()->route('coupons.index');
    }

    public function destroy(Coupon $coupon)
    {
        $this->authorize('coupons-delete');

        // log
        activity_log('delete-coupon', __METHOD__, $coupon);

        $coupon->delete();
        return back();
    }
}
