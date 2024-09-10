<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'inventory_id',
        'status',
        'desc',
        'count'
    ];

    const STATUS = [
        'pending_purchase' => 'درانتظار خرید',
        'purchase_done' => 'خریداری شد'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
}
