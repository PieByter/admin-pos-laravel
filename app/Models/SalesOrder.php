<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'issue_date',
        'customer_id',
        'notes',
        'due_date',
        'payment_date',
        'total_amount',
        'status',
        'payment_method',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'payment_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    // ✅ Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function salesOrderItems()
    {
        return $this->hasMany(SalesOrderItem::class);
    }

    public function returnOrders()
    {
        return $this->morphMany(ReturnOrder::class, 'original_item');
    }

    // ✅ Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeDebt($query)
    {
        return $query->where('status', 'debt');
    }

    public function scopeReturned($query)
    {
        return $query->where('status', 'returned');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeByCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('issue_date', [$startDate, $endDate]);
    }

    public function scopePaid($query)
    {
        return $query->whereNotNull('payment_date');
    }

    public function scopeUnpaid($query)
    {
        return $query->whereNull('payment_date');
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
            ->whereNull('payment_date');
    }

    // ✅ Accessors
    public function getIsDraftAttribute()
    {
        return $this->status === 'draft';
    }

    public function getIsProcessingAttribute()
    {
        return $this->status === 'processing';
    }

    public function getIsCompletedAttribute()
    {
        return $this->status === 'completed';
    }

    public function getIsDebtAttribute()
    {
        return $this->status === 'debt';
    }

    public function getIsReturnedAttribute()
    {
        return $this->status === 'returned';
    }

    public function getIsCancelledAttribute()
    {
        return $this->status === 'cancelled';
    }

    public function getIsPaidAttribute()
    {
        return !is_null($this->payment_date);
    }

    public function getIsUnpaidAttribute()
    {
        return is_null($this->payment_date);
    }

    public function getIsOverdueAttribute()
    {
        return $this->due_date && $this->due_date < now() && $this->is_unpaid;
    }

    public function getTotalItemsAttribute()
    {
        return $this->salesOrderItems()->sum('quantity');
    }

    public function getTotalItemTypesAttribute()
    {
        return $this->salesOrderItems()->count();
    }

    public function getDaysOverdueAttribute()
    {
        if (!$this->due_date || $this->is_paid) {
            return 0;
        }

        return now()->diffInDays($this->due_date, false);
    }

    public function getTotalReturnedItemsAttribute()
    {
        return $this->salesOrderItems()->sum('returned_quantity');
    }

    public function getReturnPercentageAttribute()
    {
        $totalItems = $this->total_items;
        if ($totalItems == 0) return 0;

        return ($this->total_returned_items / $totalItems) * 100;
    }

    // ✅ Helper Methods
    public function calculateTotal()
    {
        $this->total_amount = $this->salesOrderItems()->sum('subtotal');
        $this->save();
        return $this->total_amount;
    }

    public function markAsPaid($paymentDate = null)
    {
        $this->payment_date = $paymentDate ?? now();
        $this->status = 'completed';
        $this->save();
    }

    public function markAsDebt()
    {
        $this->status = 'debt';
        $this->save();
    }

    public function cancel($reason = null)
    {
        $this->status = 'cancelled';
        if ($reason) {
            $this->notes = $this->notes . "\nCancellation reason: " . $reason;
        }
        $this->save();
    }

    public function processOrder()
    {
        $this->status = 'processing';
        $this->save();
    }

    // public function completeOrder()
    // {
    //     $this->status = 'completed';
    //     if (!$this->payment_date) {
    //         $this->payment_date = now()->toDateString();
    //     }
    //     $this->save();
    // }
}