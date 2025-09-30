<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_name',
        'description',
    ];

    // ✅ Relationships
    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function unitConversionsFrom()
    {
        return $this->hasMany(UnitConversion::class, 'from_unit_id');
    }

    public function unitConversionsTo()
    {
        return $this->hasMany(UnitConversion::class, 'to_unit_id');
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
    public function scopeWithItemCount($query)
    {
        return $query->withCount('items');
    }

    // ✅ Accessors
    public function getItemCountAttribute()
    {
        return $this->items()->count();
    }
}
