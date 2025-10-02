<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
        'job_title',
        'position',
        'profile_picture',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ✅ Relationships
    public function createdSalesOrders()
    {
        return $this->hasMany(SalesOrder::class, 'created_by');
    }

    public function updatedSalesOrders()
    {
        return $this->hasMany(SalesOrder::class, 'updated_by');
    }

    public function createdPurchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class, 'created_by');
    }

    public function updatedPurchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class, 'updated_by');
    }

    public function approvedReturns()
    {
        return $this->hasMany(ReturnOrder::class, 'approved_by');
    }

    public function createdReturns()
    {
        return $this->hasMany(ReturnOrder::class, 'created_by');
    }

    public function updatedReturns()
    {
        return $this->hasMany(ReturnOrder::class, 'updated_by');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'user_id');
    }

    // ✅ Scopes
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeByPosition($query, $position)
    {
        return $query->where('position', $position);
    }

    public function scopeByJobTitle($query, $jobTitle)
    {
        return $query->where('job_title', $jobTitle);
    }

    public function scopeActive($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    // ✅ Accessors
    public function getFullNameAttribute()
    {
        return $this->username;
    }

    public function getIsAdminAttribute()
    {
        return in_array($this->role, ['admin', 'superadmin']);
    }

    public function getIsSuperAdminAttribute()
    {
        return $this->role === 'superadmin';
    }

    public function getIsActiveAttribute()
    {
        return !is_null($this->email_verified_at);
    }

    public function getProfilePictureUrlAttribute()
    {
        if ($this->profile_picture) {
            return asset('storage/profile_pictures/' . $this->profile_picture);
        }

        // Default avatar
        return asset('images/default-avatar.png');
    }

    // ✅ Mutators
    public function setUsernameAttribute($value)
    {
        $this->attributes['username'] = strtolower($value);
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    // ✅ Helper Methods
    public function hasPermissionTo($permission)
    {
        // Menggunakan Spatie Permission
        return $this->can($permission);
    }

    public function assignDefaultRole()
    {
        if (!$this->hasAnyRole()) {
            $this->assignRole('viewer'); // Default role
        }
    }

    public function canAccessPanel()
    {
        return $this->is_admin || $this->hasRole(['admin', 'superadmin']);
    }

    public function getInitialsAttribute()
    {
        $words = explode(' ', $this->username);
        $initials = '';

        foreach ($words as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }

        return substr($initials, 0, 2);
    }

    // ✅ Boot method
    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            // Assign default role when user is created
            $user->assignDefaultRole();
        });
    }
}