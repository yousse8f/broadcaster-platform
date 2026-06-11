<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Settings extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        // General Settings
        'company_name',
        'support_email',
        'support_url',
        'timezone',
        'logo',
        
        // License Settings
        'default_expiration',
        'default_devices_limit',
        'heartbeat_timeout',
        'validation_timeout',
        
        // Security Settings
        'rate_limit',
        'max_activations_per_day',
        'api_secret',
        'allowed_origins',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'default_expiration' => 'integer',
            'default_devices_limit' => 'integer',
            'heartbeat_timeout' => 'integer',
            'validation_timeout' => 'integer',
            'rate_limit' => 'integer',
            'max_activations_per_day' => 'integer',
            'allowed_origins' => 'array',
        ];
    }

    /**
     * Get the current settings instance (singleton pattern).
     * Creates default settings if none exist.
     */
    public static function getCurrent(): self
    {
        return Cache::remember('settings.current', 3600, function () {
            return self::firstOrCreate([], [
                // General Settings
                'company_name' => 'Broadcast Platform',
                'support_email' => null,
                'support_url' => null,
                'timezone' => 'UTC',
                'logo' => null,
                
                // License Settings
                'default_expiration' => 365,
                'default_devices_limit' => 1,
                'heartbeat_timeout' => 300,
                'validation_timeout' => 60,
                
                // Security Settings
                'rate_limit' => 60,
                'max_activations_per_day' => 10,
                'api_secret' => null,
                'allowed_origins' => null,
            ]);
        });
    }

    /**
     * Clear the settings cache.
     */
    public function clearCache(): void
    {
        Cache::forget('settings.current');
    }

    /**
     * Get the allowed origins as an array.
     */
    public function getAllowedOriginsArray(): array
    {
        return $this->allowed_origins ?? ['*'];
    }

    /**
     * Set the allowed origins from an array.
     */
    public function setAllowedOriginsFromArray(array $origins): void
    {
        $this->allowed_origins = $origins;
    }
}
