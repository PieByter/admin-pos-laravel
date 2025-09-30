<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'issue_date',
        'supplier_id',
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
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function purchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrderItem::class);
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

    public function scopeBySupplier($query, $supplierId)
    {
        return $query->where('supplier_id', $supplierId);
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

    public function getIsPaidAttribute()
    {
        return !is_null($this->payment_date);
    }

    public function getIsUnpaidAttribute()
    {
        return is_null($this->payment_date);
    }

    public function getTotalItemsAttribute()
    {
        return $this->purchaseOrderItems()->sum('quantity');
    }

    public function getTotalItemTypesAttribute()
    {
        return $this->purchaseOrderItems()->count();
    }

    public function getDaysOverdueAttribute()
    {
        if (!$this->due_date || $this->is_paid) {
            return 0;
        }

        return now()->diffInDays($this->due_date, false);
    }

    // ✅ Helper Methods
    public function calculateTotal()
    {
        $this->total_amount = $this->purchaseOrderItems()->sum('subtotal');
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
}
