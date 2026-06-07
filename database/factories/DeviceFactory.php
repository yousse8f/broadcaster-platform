<?php

namespace Database\Factories;

use App\Models\Device;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Device>
 */
class DeviceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'license_id' => \App\Models\License::factory(),
            'device_id' => strtoupper(fake()->bothify('PC###')),
            'device_name' => fake()->firstName() . "'s " . fake()->randomElement(['PC', 'Laptop', 'Desktop']),
            'user_agent' => fake()->userAgent(),
            'ip_address' => fake()->ipv4(),
            'status' => 'active',
            'first_activated_at' => now(),
            'last_seen' => now(),
            'operating_system' => fake()->randomElement(['windows', 'mac', 'linux']),
        ];
    }
}
