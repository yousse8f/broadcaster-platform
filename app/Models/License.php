<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'license_key',
        'status',
        'expires_at',
        'expiry_date',
        'allowed_devices',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'expiry_date' => 'datetime',
        ];
    }

    /**
     * Get the user that owns the license.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the devices for the license.
     */
    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    /**
     * Check if the license is active and not expired.
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && 
               ($this->expires_at === null || $this->expires_at->isFuture());
    }

    /**
     * Check if the license has reached the device limit.
     */
    public function hasReachedDeviceLimit(): bool
    {
        return $this->devices()->where('status', 'active')->count() >= $this->allowed_devices;
    }

    /**
     * Get the count of active devices for this license.
     */
    public function getActiveDevicesCountAttribute(): int
    {
        return $this->devices()->where('status', 'active')->count();
    }

    /**
     * Get the count of online devices for this license.
     */
    public function getOnlineDevicesCountAttribute(): int
    {
        return $this->devices()->online()->count();
    }
}
