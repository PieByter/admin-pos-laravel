<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderItem extends Model
{
    use HasFactory;

    protected $table = 'sales_order_items';

    protected $fillable = [
        'sales_order_id',
        'item_id',
        'unit_id',
        'quantity',
        'base_quantity',
        'sell_price',
        'subtotal',
        'discount',
        'discount_type',
        'status',
        'returned_quantity',
        'returned_base_quantity',
        'return_reason',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'base_quantity' => 'integer',
        'sell_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'returned_quantity' => 'integer',
        'returned_base_quantity' => 'integer',
    ];

    // ✅ Relationships
    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class);
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

    public function getTotalRevenueAttribute()
    {
        return round($this->quantity * $this->sell_price, 2);
    }

    public function getNetRevenueAttribute()
    {
        return round($this->net_quantity * $this->sell_price, 2);
    }

    // ✅ Mutators
    public function setSubtotalAttribute($value)
    {
        $this->attributes['subtotal'] = round($this->quantity * $this->sell_price, 2);
    }

    // ✅ Helper Methods
    // public function calculateSubtotal()
    // {
    //     $this->subtotal = $this->quantity * $this->sell_price;
    //     $this->save();
    //     return $this->subtotal;
    // }

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

        // Recalculate sales order total
        $this->salesOrder->calculateTotal();

        // Update item stock (increase back)
        $this->item->increment('stock', $returnedBaseQty ?? $returnedQty);
    }

    public function cancelItem($reason = null)
    {
        $this->status = 'cancelled';
        if ($reason) {
            $this->return_reason = $reason;
        }
        $this->save();

        // Return stock to item
        $this->item->increment('stock', $this->base_quantity);

        // Recalculate sales order total
        $this->salesOrder->calculateTotal();
    }

    public function exchangeItem($newItemId, $newQuantity, $newPrice, $reason = null)
    {
        $this->status = 'exchanged';
        if ($reason) {
            $this->return_reason = $reason;
        }
        $this->save();

        // Return original stock
        $this->item->increment('stock', $this->base_quantity);

        // Create new item entry for exchange
        // (This might need additional logic based on your business rules)
    }

    // ✅ Boot method
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Auto-calculate subtotal if not set
            if (!$model->subtotal) {
                $model->subtotal = round($model->quantity * $model->sell_price, 2);
            }
        });

        static::saved(function ($model) {
            // Recalculate sales order total when item is saved
            $model->salesOrder->calculateTotal();
        });

        static::created(function ($model) {
            // Decrease item stock when sales order item is created
            $model->item->decrement('stock', $model->base_quantity);
        });

        static::deleted(function ($model) {
            // Return stock when item is deleted
            $model->item->increment('stock', $model->base_quantity - $model->returned_base_quantity);

            // Recalculate sales order total
            $model->salesOrder->calculateTotal();
        });
    }
}