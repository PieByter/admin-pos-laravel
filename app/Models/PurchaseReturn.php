<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturn extends Model
{
    use HasFactory;

    protected $table = 'returns';

    protected $fillable = [
        'return_number',
        'return_date',
        'return_type',
        'original_item_type',
        'original_item_id',
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
        'approved_date'
    ];

    protected $casts = [
        'return_date' => 'date',
        'approved_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'total_return_amount' => 'decimal:2'
    ];

    // Scope untuk purchase returns
    public function scopePurchaseReturns($query)
    {
        return $query->where('return_type', 'purchase');
    }

    // Relationship ke return items menggunakan PurchaseReturnItem
    public function items()
    {
        return $this->hasMany(PurchaseReturnItem::class, 'return_id');
    }

    // Relationship ke original purchase order
    public function originalPurchase()
    {
        return $this->belongsTo(PurchaseOrder::class, 'original_item_id')
            ->where('original_item_type', 'purchase');
    }

    // Relationship ke supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Relationship ke customer (untuk sales returns)
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Relationship ke user yang approve
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Relationship ke user yang create
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relationship ke user yang update
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Method untuk calculate total return amount
    public function calculateTotalReturnAmount()
    {
        return $this->items()->sum('subtotal');
    }

    // Method untuk update total return amount
    public function updateTotalReturnAmount()
    {
        $this->update([
            'total_return_amount' => $this->calculateTotalReturnAmount()
        ]);
    }

    // Static method untuk generate return number
    public static function generateReturnNumber($type = 'purchase')
    {
        $prefix = $type === 'purchase' ? 'RPB' : 'RJL';
        $date = now()->format('Ymd');

        $lastReturn = static::where('return_type', $type)
            ->whereDate('created_at', now())
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastReturn ? intval(substr($lastReturn->return_number, -4)) + 1 : 1;

        return $prefix . $date . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}