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
        'last_seen',
        'is_active',
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
            'is_active' => 'boolean',
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
}
