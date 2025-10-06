<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitConversion extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'unit_id',
        'conversion_value',
        'description',
    ];

    protected $casts = [
        'conversion_value' => 'integer',
    ];

    // ✅ Relationships
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // public function fromUnit()
    // {
    //     return $this->belongsTo(Unit::class, 'from_unit_id');
    // }

    // public function toUnit()
    // {
    //     return $this->belongsTo(Unit::class, 'to_unit_id');
    // }
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    // ✅ Scopes
    public function scopeForItem($query, $itemId)
    {
        return $query->where('item_id', $itemId);
    }

    // public function scopeFromUnit($query, $unitId)
    // {
    //     return $query->where('from_unit_id', $unitId);
    // }

    // public function scopeToUnit($query, $unitId)
    // {
    //     return $query->where('to_unit_id', $unitId);
    // }

    // ✅ Helper Methods
    public function convert($quantity)
    {
        return $quantity * $this->conversion_value;
    }

    public function reverseConvert($quantity)
    {
        return $quantity / $this->conversion_value;
    }
}