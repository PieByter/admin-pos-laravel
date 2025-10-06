<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesReturn extends Model
{
    use HasFactory;

    protected $table = 'sales_returns';

    protected $fillable = [
        'return_number',
        'return_date',
        'sales_order_id',
        'customer_id',
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
        'approved_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'total_return_amount' => 'decimal:2'
    ];

    // Relasi ke sales return items
    public function items()
    {
        return $this->hasMany(SalesReturnItem::class, 'sales_return_id');
    }

    // Relasi ke sales order
    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class, 'sales_order_id');
    }

    // Relasi ke customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Relasi ke user yang approve
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Relasi ke user yang create
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi ke user yang update
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
    public static function generateReturnNumber()
    {
        $prefix = 'RJL';
        $date = now()->format('Ymd');

        $lastReturn = static::whereDate('created_at', now())
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastReturn ? intval(substr($lastReturn->return_number, -4)) + 1 : 1;

        return $prefix . $date . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}