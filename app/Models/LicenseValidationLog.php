<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LicenseValidationLog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'license_key',
        'ip_address',
        'result',
        'message',
        'license_id',
    ];

    /**
     * Disable updated_at timestamp (only created_at is needed).
     */
    public const UPDATED_AT = null;

    /**
     * Get the license associated with the validation log.
     */
    public function license(): BelongsTo
    {
        return $this->belongsTo(License::class);
    }
}
