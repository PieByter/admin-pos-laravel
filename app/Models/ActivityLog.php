<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'activity',
    ];

    // Relasi ke user (siapa yang melakukan aktivitas)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope untuk filter aktivitas tertentu
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderByDesc('created_at')->limit($limit);
    }
}