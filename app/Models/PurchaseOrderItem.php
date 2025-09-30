<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    use HasFactory;

    protected $table = 'purchase_order_items';

    protected $fillable = [
        'purchase_order_id',
        'item_id',
        'unit_id',
        'quantity',
        'base_quantity',
        'buy_price',
        'subtotal',
        'status',
        'returned_quantity',
        'returned_base_quantity',
        'return_reason',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'base_quantity' => 'integer',
        'buy_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'returned_quantity' => 'integer',
        'returned_base_quantity' => 'integer',
    ];

    // ✅ Relationships
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function returnItems()
    {
        return $this->morphMany(ReturnOrderItem::class, 'original_item');
    }

    // ✅ Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeNormal($query)
    {
        return $query->where('status', 'normal');
    }

    public function scopeReturned($query)
    {
        return $query->where('status', 'returned');
    }

    public function scopeExchanged($query)
    {
        return $query->where('status', 'exchanged');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeByItem($query, $itemId)
    {
        return $query->where('item_id', $itemId);
    }

    public function scopeHasReturns($query)
    {
        return $query->where('returned_quantity', '>', 0);
    }

    // ✅ Accessors
    public function getIsNormalAttribute()
    {
        return $this->status === 'normal';
    }

    public function getIsReturnedAttribute()
    {
        return $this->status === 'returned';
    }

    public function getIsExchangedAttribute()
    {
        return $this->status === 'exchanged';
    }

    public function getIsCancelledAttribute()
    {
        return $this->status === 'cancelled';
    }

    public function getHasReturnsAttribute()
    {
        return $this->returned_quantity > 0;
    }

    public function getNetQuantityAttribute()
    {
        return $this->quantity - $this->returned_quantity;
    }

    public function getNetBaseQuantityAttribute()
    {
        return $this->base_quantity - $this->returned_base_quantity;
    }

    public function getReturnPercentageAttribute()
    {
        if ($this->quantity == 0) return 0;
        return ($this->returned_quantity / $this->quantity) * 100;
    }

    public function getTotalCostAttribute()
    {
        return $this->quantity * $this->buy_price;
    }

    public function getNetCostAttribute()
    {
        return $this->net_quantity * $this->buy_price;
    }

    // ✅ Mutators
    public function setSubtotalAttribute($value)
    {
        $this->attributes['subtotal'] = $this->quantity * $this->buy_price;
    }

    // ✅ Helper Methods
    public function calculateSubtotal()
    {
        $this->subtotal = $this->quantity * $this->buy_price;
        $this->save(); // ✅ BENAR - Perlu save()
        return $this->subtotal; // ✅ BENAR - Perlu return
    }

    public function addReturn($returnedQty, $returnedBaseQty = null, $reason = null)
    {
        $this->returned_quantity += $returnedQty;
        $this->returned_base_quantity += $returnedBaseQty ?? $returnedQty;

        if ($reason) {
            $this->return_reason = $reason;
        }

        // Update status if fully returned
        if ($this->returned_quantity >= $this->quantity) {
            $this->status = 'returned';
        }

        $this->save();

        // Recalculate purchase order total
        $this->purchaseOrder->calculateTotal();
    }

    public function cancelItem($reason = null)
    {
        $this->status = 'cancelled';
        if ($reason) {
            $this->return_reason = $reason;
        }
        $this->save();

        // Recalculate purchase order total
        $this->purchaseOrder->calculateTotal();
    }

    // ✅ Boot method
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Auto-calculate subtotal if not set
            if (!$model->subtotal) {
                $model->subtotal = $model->quantity * $model->buy_price;
            }
        });

        static::saved(function ($model) {
            // Recalculate purchase order total when item is saved
            $model->purchaseOrder->calculateTotal();
        });
    }
}
