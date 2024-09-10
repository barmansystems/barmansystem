<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $guarded = [];

    const TYPE = [
        'printer' => 'پرینتر',
        'scanner' => 'اسکنر',
        'projector' => 'پروژکتور',
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function in_outs()
    {
        return $this->hasMany(InOut::class);
    }

    public function getInputCount()
    {
        $inventory_report_id = InventoryReport::where('type','input')->pluck('id');
        return $this->in_outs()->whereIn('inventory_report_id', $inventory_report_id)->sum('count');
    }

    public function getOutputCount()
    {
        $inventory_report_id = InventoryReport::where('type','output')->pluck('id');
        return $this->in_outs()->whereIn('inventory_report_id', $inventory_report_id)->sum('count');
    }
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
