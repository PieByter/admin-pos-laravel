<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_code',
        'item_name',
        'item_group_id',
        'item_category_id',
        'unit_id',
        'buy_price',
        'sell_price',
        'stock',
        'items_description',
    ];

    protected $casts = [
        'buy_price' => 'decimal:2',
        'sell_price' => 'decimal:2',
        'stock' => 'integer',
    ];

    // ✅ Relationships
    public function itemGroup()
    {
        return $this->belongsTo(ItemGroup::class);
    }

    public function itemCategory()
    {
        return $this->belongsTo(ItemCategory::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function unitConversions()
    {
        return $this->hasMany(UnitConversion::class);
    }

    public function salesOrderItems()
    {
        return $this->hasMany(SalesOrderItem::class);
    }

    public function purchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function prePurchaseOrderItems()
    {
        return $this->hasMany(PrePurchaseOrderItem::class);
    }

    public function returnItems()
    {
        return $this->hasMany(ReturnOrderItem::class);
    }

    // ✅ Scopes
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('stock', '<=', 0);
    }

    public function scopeLowStock($query, $threshold = 10)
    {
        return $query->where('stock', '<=', $threshold)->where('stock', '>', 0);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('item_category_id', $categoryId);
    }

    public function scopeByGroup($query, $groupId)
    {
        return $query->where('item_group_id', $groupId);
    }

    // ✅ Accessors
    public function getIsInStockAttribute()
    {
        return $this->stock > 0;
    }

    public function getIsOutOfStockAttribute()
    {
        return $this->stock <= 0;
    }

    public function getProfitMarginAttribute()
    {
        if ($this->buy_price == 0) return 0;
        return (($this->sell_price - $this->buy_price) / $this->buy_price) * 100;
    }

    public function getProfitPerUnitAttribute()
    {
        return $this->sell_price - $this->buy_price;
    }

    public function getStockValueAttribute()
    {
        return $this->stock * $this->buy_price;
    }
}
