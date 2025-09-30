<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_email',
        'phone_number',
        'company_name',
        'contact_person',
        'address',
        'status',
    ];

    // Relasi: Supplier punya banyak PurchaseOrder
    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    // Scope untuk supplier aktif
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope untuk customer tidak aktif
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }
}
