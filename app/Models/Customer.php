<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
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
        'description',
    ];

    // Relasi: Customer punya banyak SalesOrder
    public function salesOrders()
    {
        return $this->hasMany(SalesOrder::class);
    }

    // Scope untuk customer aktif
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
