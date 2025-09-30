<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrePurchaseOrderItem extends Model
{
    protected $table = 'pre_purchase_order_items';

    protected $fillable = [
        'pre_purchase_order_id',
        'item_id',
        'unit_id',
        'quantity',
        'base_quantity',
        'price',
        'subtotal',
    ];

    // Relasi ke PrePurchaseOrder
    public function prePurchaseOrder()
    {
        return $this->belongsTo(PrePurchaseOrder::class);
    }

    // Relasi ke Item
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // Relasi ke Unit
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
