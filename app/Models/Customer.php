<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $guarded = [];

    const TYPE = [
        'government' => 'دولتی',
        'private' => 'خصوصی',
    ];

    const CUSTOMER_TYPE = [
//        'system' => 'سامانه',
//        'tehran' => 'تهران',
//        'city' => 'شهرستان',
//    'single-sale' => 'تک فروشی',
        'setad'=>'سامانه ستاد',
        'online-sale'=>'فروش اینترنتی',
        'free-sale'=>'آزاد (بازار)',
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function buy_orders()
    {
        return $this->hasMany(BuyOrder::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
