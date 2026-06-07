<?php

namespace Database\Factories;

use App\Models\License;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<License>
 */
class LicenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'license_key' => strtoupper(fake()->bothify('????-????-####-####')),
            'status' => 'active',
            'expires_at' => now()->addYear(),
            'allowed_devices' => 1,
        ];
    }
}
