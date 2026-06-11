<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecurityAuditLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'event_type',
        'ip_address',
        'user_agent',
        'license_key',
        'device_id',
        'details',
        'severity',
        'occurred_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'occurred_at' => 'datetime',
            'details' => 'array',
        ];
    }

    /**
     * Log a security event.
     */
    public static function logEvent(array $data): self
    {
        return self::create([
            'event_type' => $data['event_type'],
            'ip_address' => $data['ip_address'] ?? null,
            'user_agent' => $data['user_agent'] ?? null,
            'license_key' => $data['license_key'] ?? null,
            'device_id' => $data['device_id'] ?? null,
            'details' => $data['details'] ?? null,
            'severity' => $data['severity'] ?? 'low',
            'occurred_at' => $data['occurred_at'] ?? now(),
        ]);
    }
}
