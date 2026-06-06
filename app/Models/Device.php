<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'license_id',
        'device_id',
        'device_name',
        'user_agent',
        'ip_address',
        'status',
        'first_activated_at',
        'operating_system',
        'last_seen',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'last_seen' => 'datetime',
            'first_activated_at' => 'datetime',
        ];
    }

    /**
     * Get the license that owns the device.
     */
    public function license()
    {
        return $this->belongsTo(License::class);
    }

    /**
     * Get the user that owns the device through the license.
     */
    public function user()
    {
        return $this->license->user;
    }

    /**
     * Update the last_seen timestamp.
     */
    public function updateLastSeen(): void
    {
        $this->update(['last_seen' => now()]);
    }

    /**
     * Check if the device is currently online (last seen within 5 minutes).
     */
    public function isOnline(): bool
    {
        return $this->last_seen && $this->last_seen->gt(now()->subMinutes(5));
    }

    /**
     * Check if the device is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if the device is suspended.
     */
    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    /**
     * Check if the device is revoked.
     */
    public function isRevoked(): bool
    {
        return $this->status === 'revoked';
    }

    /**
     * Get the online status text.
     */
    public function getOnlineStatusAttribute(): string
    {
        return $this->isOnline() ? 'Online' : 'Offline';
    }

    /**
     * Get the status badge class.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'active' => 'status-active',
            'suspended' => 'status-suspended',
            'revoked' => 'status-revoked',
            default => 'status-inactive',
        };
    }

    /**
     * Get the online status badge class.
     */
    public function getOnlineStatusBadgeClassAttribute(): string
    {
        return $this->isOnline() ? 'status-online' : 'status-offline';
    }

    /**
     * Activate the device.
     */
    public function activate(): void
    {
        $this->update(['status' => 'active']);
    }

    /**
     * Suspend the device.
     */
    public function suspend(): void
    {
        $this->update(['status' => 'suspended']);
    }

    /**
     * Revoke the device.
     */
    public function revoke(): void
    {
        $this->update(['status' => 'revoked']);
    }

    /**
     * Scope a query to only include active devices.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include suspended devices.
     */
    public function scopeSuspended($query)
    {
        return $query->where('status', 'suspended');
    }

    /**
     * Scope a query to only include revoked devices.
     */
    public function scopeRevoked($query)
    {
        return $query->where('status', 'revoked');
    }

    /**
     * Scope a query to only include online devices.
     */
    public function scopeOnline($query)
    {
        return $query->where('last_seen', '>', now()->subMinutes(5));
    }

    /**
     * Scope a query to only include offline devices.
     */
    public function scopeOffline($query)
    {
        return $query->where(function($q) {
            $q->whereNull('last_seen')
              ->orWhere('last_seen', '<=', now()->subMinutes(5));
        });
    }
}
