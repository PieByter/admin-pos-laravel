<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrePurchaseOrder extends Model
{
    protected $table = 'pre_purchase_orders';

    protected $fillable = [
        'po_number',
        'issue_date',
        'supplier_id',
        'notes',
        'total_amount',
        'tax',
        'due_date',
        'payment_date',
        'status',
        'payment_method',
        'created_by',
        'updated_by',
    ];

    // Relasi ke supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Relasi ke user pembuat
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi ke user pengubah
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Relasi ke item-item PrePurchaseOrder
    public function items()
    {
        return $this->hasMany(PrePurchaseOrderItem::class, 'pre_purchase_order_id');
    }
}
