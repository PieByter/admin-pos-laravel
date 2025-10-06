<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesReturnItem extends Model
{
    use HasFactory;

    protected $table = 'sales_return_items';

    protected $fillable = [
        'sales_return_id',
        'sales_order_item_id',
        'item_id',
        'unit_id',
        'return_quantity',
        'return_base_quantity',
        'original_price',
        'return_price',
        'subtotal',
        'condition',
        'item_notes'
    ];

    protected $casts = [
        'return_quantity' => 'integer',
        'return_base_quantity' => 'integer',
        'original_price' => 'decimal:2',
        'return_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relasi ke sales return
    public function salesReturn()
    {
        return $this->belongsTo(SalesReturn::class, 'sales_return_id');
    }

    // Relasi ke sales order item
    public function salesOrderItem()
    {
        return $this->belongsTo(SalesOrderItem::class, 'sales_order_item_id');
    }

    // Relasi ke item
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // Relasi ke unit
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    // Accessor untuk total return value
    public function getTotalReturnValueAttribute()
    {
        return $this->return_quantity * $this->return_price;
    }

    // Accessor untuk price difference
    public function getPriceDifferenceAttribute()
    {
        return $this->original_price - $this->return_price;
    }

    // Method untuk cek price difference
    public function hasPriceDifference()
    {
        return $this->original_price != $this->return_price;
    }

    // Method untuk calculate subtotal
    public function calculateSubtotal()
    {
        return $this->return_quantity * $this->return_price;
    }

    // Scope untuk filter by condition
    public function scopeByCondition($query, $condition)
    {
        return $query->where('condition', $condition);
    }
}