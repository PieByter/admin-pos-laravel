<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturnItem extends Model
{
    use HasFactory;

    protected $table = 'return_items';

    protected $fillable = [
        'return_id',
        'original_item_type',
        'original_item_id',
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
        'return_quantity' => 'decimal:2',
        'return_base_quantity' => 'decimal:2',
        'original_price' => 'decimal:2',
        'return_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationship ke return
    public function return()
    {
        return $this->belongsTo(PurchaseReturn::class, 'return_id');
    }

    // Relationship ke item
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // Relationship ke unit
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    // Relationship ke original item (polymorphic)
    public function originalItem()
    {
        switch ($this->original_item_type) {
            case 'purchase_item':
                return $this->belongsTo(PurchaseOrderItem::class, 'original_item_id');
            case 'sales_item':
                return $this->belongsTo(SalesOrderItem::class, 'original_item_id');
            default:
                return null;
        }
    }

    // Method untuk mendapatkan original purchase item
    public function originalPurchaseItem()
    {
        if ($this->original_item_type === 'purchase_item') {
            return $this->belongsTo(PurchaseOrderItem::class, 'original_item_id')->first();
        }
        return null;
    }

    // Method untuk mendapatkan original sales item
    public function originalSalesItem()
    {
        if ($this->original_item_type === 'sales_item') {
            return $this->belongsTo(SalesOrderItem::class, 'original_item_id')->first();
        }
        return null;
    }

    // Accessor untuk mendapatkan total return value
    public function getTotalReturnValueAttribute()
    {
        return $this->return_quantity * $this->return_price;
    }

    // Accessor untuk mendapatkan price difference
    public function getPriceDifferenceAttribute()
    {
        return $this->original_price - $this->return_price;
    }

    // Method untuk check apakah ada price difference
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

    // Scope untuk filter by original item type
    public function scopeByOriginalType($query, $type)
    {
        return $query->where('original_item_type', $type);
    }
}