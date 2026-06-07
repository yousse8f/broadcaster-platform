<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivationLog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'license_key',
        'device_id',
        'device_name',
        'ip_address',
        'success',
        'message',
        'license_id',
    ];

    /**
     * Disable updated_at timestamp (only created_at is needed).
     */
    public const UPDATED_AT = null;

    /**
     * Get the license associated with the activation log.
     */
    public function license(): BelongsTo
    {
        return $this->belongsTo(License::class);
    }

    /**
     * Scope a query to only include successful activations.
     */
    public function scopeSuccessful($query)
    {
        return $query->where('success', true);
    }

    /**
     * Scope a query to only include failed activations.
     */
    public function scopeFailed($query)
    {
        return $query->where('success', false);
    }

    /**
     * Scope a query to filter by license key.
     */
    public function scopeByLicenseKey($query, string $licenseKey)
    {
        return $query->where('license_key', $licenseKey);
    }

    /**
     * Scope a query to filter by device ID.
     */
    public function scopeByDeviceId($query, string $deviceId)
    {
        return $query->where('device_id', $deviceId);
    }
}
