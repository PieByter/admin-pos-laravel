<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemGroup extends Model
{
    use HasFactory;

    protected $table = 'item_groups';

    protected $fillable = [
        'group_name',
        'description',
    ];

    // ✅ Relationships
    public function items()
    {
        return $this->hasMany(Item::class);
    }

    // ✅ Scopes
    public function scopeWithItemCount($query)
    {
        return $query->withCount('items');
    }

    // ✅ Accessors
    public function getItemCountAttribute()
    {
        return $this->items()->count();
    }
}
