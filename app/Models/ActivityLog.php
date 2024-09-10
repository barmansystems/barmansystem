<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $guarded = [];

    const ACTIVITY_NAMES = [
        // users
        'create-user' => 'ایجاد کاربر',
        'edit-user' => 'ویرایش کاربر',
        'delete-user' => 'ویرایش کاربر',

        // roles
        'create-role' => 'ایجاد نقش',
        'edit-role' => 'ویرایش نقش',
        'delete-role' => 'ویرایش نقش',

        // tasks
        'create-task' => 'ایجاد وظیفه',
        'edit-task' => 'ویرایش وظیفه',
        'delete-task' => 'حذف وظیفه',
        'task-change-status' => 'تغییر وضعیت وظیفه',
        'task-add-desc' => 'افزودن توضیحات وظیفه',

        // notes
        'create-note' => 'ایجاد یادداشت',
        'delete-note' => 'حذف یادداشت',

        // leaves
        'create-leave' => 'درخواست مرخصی',
        'edit-leave' => 'تعیین وضعیت مرخصی',
        'delete-leave' => 'حذف درخواست مرخصی',

        // reports
        'create-report' => 'ثبت گزارش روزانه',
        'edit-report' => 'ویرایش گزارش روزانه',
        'delete-report' => 'حذف گزارش روزانه',

        // file manager
        'create-folder' => 'ایجاد پوشه',
        'upload-file' => 'آپلود فایل',
        'edit-file-name' => 'ویرایش نام فایل',
        'delete-file' => 'حذف فایل',
        'moving-file' => 'عملیات انتقال فایل',
        'cancel-move-file' => 'لغو انتقال فایل',
        'move-file' => 'تایید انتقال فایل',

        // products
        'create-product' => 'ایجاد محصول',
        'edit-product' => 'ویرایش محصول',
        'delete-product' => 'حذف محصول',

        // coupons
        'create-coupon' => 'ایجاد کد تخفیف',
        'edit-coupon' => 'ویرایش کد تخفیف',
        'delete-coupon' => 'حذف کد تخفیف',

        // invoices
        'create-invoice' => 'ثبت سفارش',
        'edit-invoice' => 'ویرایش سفارش',
        'delete-invoice' => 'حذف سفارش',
        'invoice-action' => 'اقدام سفارش',
        'delete-invoice-file' => 'حذف فایل پیش فاکتور',
        'delete-factor-file' => 'حذف فایل فاکتور',
        'order-change-status' => 'تغییر وضعیت سفارش',
        'order-add-desc' => 'افزودن توضحات سفارش',

        // buy-orders
        'create-buy-order' => 'ثبت سفارش خرید',
        'edit-buy-order' => 'ویرایش سفارش خرید',
        'delete-buy-order' => 'حذف سفارش خرید',
        'buy-order-change-status' => 'تغییر وضعیت سفارش خرید',

        // sales-reports
        'create-sale-report' => 'ثبت گزارش خرید',
        'edit-sale-report' => 'ویرایش گزارش خرید',
        'delete-sale-report' => 'حذف گزارش خرید',

        // price-requests
        'create-price-request' => 'ثبت درخواست قیمت',
        'edit-price-request' => 'تعیین قیمت',
        'delete-price-request' => 'حذف درخواست قیمت',

        // packets
        'create-packet' => 'ایجاد بسته ارسالی',
        'edit-packet' => 'ویرایش بسته ارسالی',
        'delete-packet' => 'حذف بسته ارسالی',

        // customers
        'create-customer' => 'ایجاد مشتری',
        'edit-customer' => 'ویرایش مشتری',
        'delete-customer' => 'حذف مشتری',

        // offsite-products
        'create-offsite-product' => 'ثبت محصول در فروشگاه ها',
        'edit-offsite-product' => 'ویرایش محصول در فروشگاه ها',
        'delete-offsite-product' => 'حذف محصول در فروشگاه ها',

        // guarantees
        'create-guarantee' => 'ایجاد گارانتی',
        'edit-guarantee' => 'ویرایش گارانتی',
        'delete-guarantee' => 'حذف گارانتی',

        // warehouses
        'create-warehouse' => 'ایجاد انبار',
        'edit-warehouse' => 'ویرایش انبار',
        'delete-warehouse' => 'حذف انبار',

        // inventories
        'create-inventory' => 'ایجاد کالا',
        'edit-inventory' => 'ویرایش کالا',
        'delete-inventory' => 'حذف کالا',
        'move-inventory' => 'انتقال کالا',

        // inventory reports - inputs
        'create-inventory-input' => 'ثبت ورود انبار',
        'edit-inventory-input' => 'ویرایش ورود انبار',
        'delete-inventory-input' => 'حذف ورود انبار',

        // inventory reports - output
        'create-inventory-output' => 'ثبت خروج انبار',
        'edit-inventory-output' => 'ویرایش خروج انبار',
        'delete-inventory-output' => 'حذف خروج انبار',

        // categories
        'create-category' => 'ایجاد دسته بندی',
        'edit-category' => 'ویرایش دسته بندی',
        'delete-category' => 'حذف دسته بندی',

        // tickets
        'create-ticket' => 'ثبت تیکت',
        'edit-ticket' => 'ارسال پیام',
        'delete-ticket' => 'حذف تیکت',
        'ticket-change-status' => 'تغییر وضعیت تیکت',

        // indicator
        'create-indicator' => 'ثبت نامه',
        'edit-indicator' => 'ویرایش نامه',
        'delete-indicator' => 'حذف نامه',
        //order_payment
        'payment-create-create' => 'ثبت دستور پرداخت/دریافت',
        'payment-edit-edit' => 'ویرایش دستور پرداخت/دریافت',
        'payment-delete-delete' => 'حذف دستور پرداخت/دریافت',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
