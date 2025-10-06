<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnOrder extends Model
{
    use HasFactory;

    protected $table = 'returns';

    protected $fillable = [
        'return_number',
        'return_date',
        'return_type',
        'original_item_id',
        'original_item_type',
        'original_order_type',
        'customer_id',
        'supplier_id',
        'total_return_amount',
        'status',
        'return_reason',
        'notes',
        'approved_by',
        'created_by',
        'updated_by',
        'approved_date',
    ];

    protected $casts = [
        'return_date' => 'date',
        'approved_date' => 'date',
        'total_return_amount' => 'decimal:2',
    ];

    // ✅ Polymorphic relationship ke original order (sales_order atau purchase_order)
    public function originalItem()
    {
        return $this->morphTo();
    }

    // ✅ Relationship ke customer (untuk sales return)
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // ✅ Relationship ke supplier (untuk purchase return)
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // ✅ Relationship ke users
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // ✅ Relationship ke return items
    public function returnItems()
    {
        return $this->hasMany(ReturnOrderItem::class, 'return_id');
    }

    // ✅ Scopes
    public function scopeSalesReturn($query)
    {
        return $query->where('return_type', 'sales_return');
    }

    public function scopePurchaseReturn($query)
    {
        return $query->where('return_type', 'purchase_return');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // ✅ Accessors
    public function getIsSalesReturnAttribute()
    {
        return $this->return_type === 'sales_return';
    }

    public function getIsPurchaseReturnAttribute()
    {
        return $this->return_type === 'purchase_return';
    }

    public function getIsApprovedAttribute()
    {
        return $this->status === 'approved';
    }
}