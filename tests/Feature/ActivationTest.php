<?php

namespace Tests\Feature;

use App\Models\ActivationLog;
use App\Models\Device;
use App\Models\License;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test 1: License صالح + جهاز جديد → Success
     */
    public function test_valid_license_with_new_device_success(): void
    {
        // Create a user and an active license
        $user = User::factory()->create(['role' => 'admin']);
        $license = License::factory()->create([
            'user_id' => $user->id,
            'license_key' => 'TEST-LICENSE-001',
            'status' => 'active',
            'allowed_devices' => 2,
            'expires_at' => now()->addYear(),
        ]);

        // Activate a new device
        $response = $this->postJson('/api/device/activate', [
            'license_key' => 'TEST-LICENSE-001',
            'device_id' => 'PC001',
            'device_name' => 'Test Device 1',
            'operating_system' => 'windows',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Device activated successfully',
                'license_key' => 'TEST-LICENSE-001',
                'device_id' => 'PC001',
                'device_name' => 'Test Device 1',
                'is_known_device' => false,
            ]);

        // Verify device was created
        $this->assertDatabaseHas('devices', [
            'device_id' => 'PC001',
            'license_id' => $license->id,
            'status' => 'active',
        ]);

        // Verify activation log was created
        $this->assertDatabaseHas('activation_logs', [
            'license_key' => 'TEST-LICENSE-001',
            'device_id' => 'PC001',
            'success' => true,
            'message' => 'New device activated',
        ]);
    }

    /**
     * Test 2: License صالح + جهاز معروف → Success, Known Device
     */
    public function test_valid_license_with_known_device_success(): void
    {
        // Create a user and an active license
        $user = User::factory()->create(['role' => 'admin']);
        $license = License::factory()->create([
            'user_id' => $user->id,
            'license_key' => 'TEST-LICENSE-002',
            'status' => 'active',
            'allowed_devices' => 2,
            'expires_at' => now()->addYear(),
        ]);

        // Create an existing device
        $device = Device::factory()->create([
            'license_id' => $license->id,
            'device_id' => 'PC002',
            'device_name' => 'Test Device 2',
            'status' => 'active',
            'first_activated_at' => now()->subDay(),
        ]);

        // Activate the same device again
        $response = $this->postJson('/api/device/activate', [
            'license_key' => 'TEST-LICENSE-002',
            'device_id' => 'PC002',
            'device_name' => 'Test Device 2',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Known device activated',
                'license_key' => 'TEST-LICENSE-002',
                'device_id' => 'PC002',
                'is_known_device' => true,
            ]);

        // Verify activation log was created
        $this->assertDatabaseHas('activation_logs', [
            'license_key' => 'TEST-LICENSE-002',
            'device_id' => 'PC002',
            'success' => true,
            'message' => 'Known device activated',
        ]);
    }

    /**
     * Test 3: License غير موجود → Failure
     */
    public function test_nonexistent_license_failure(): void
    {
        $response = $this->postJson('/api/device/activate', [
            'license_key' => 'NONEXISTENT-LICENSE',
            'device_id' => 'PC003',
            'device_name' => 'Test Device 3',
        ]);

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'License not found',
            ]);

        // Verify activation log was created
        $this->assertDatabaseHas('activation_logs', [
            'license_key' => 'NONEXISTENT-LICENSE',
            'device_id' => 'PC003',
            'success' => false,
            'message' => 'License not found',
        ]);
    }

    /**
     * Test 4: License منتهي → Failure
     */
    public function test_expired_license_failure(): void
    {
        // Create a user and an expired license
        $user = User::factory()->create(['role' => 'admin']);
        $license = License::factory()->create([
            'user_id' => $user->id,
            'license_key' => 'EXPIRED-LICENSE',
            'status' => 'active',
            'allowed_devices' => 2,
            'expires_at' => now()->subDay(),
        ]);

        $response = $this->postJson('/api/device/activate', [
            'license_key' => 'EXPIRED-LICENSE',
            'device_id' => 'PC004',
            'device_name' => 'Test Device 4',
        ]);

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'License expired',
            ]);

        // Verify activation log was created
        $this->assertDatabaseHas('activation_logs', [
            'license_key' => 'EXPIRED-LICENSE',
            'device_id' => 'PC004',
            'success' => false,
            'message' => 'License expired',
        ]);
    }

    /**
     * Test 5: License معلق → Failure
     */
    public function test_suspended_license_failure(): void
    {
        // Create a user and a suspended license
        $user = User::factory()->create(['role' => 'admin']);
        $license = License::factory()->create([
            'user_id' => $user->id,
            'license_key' => 'SUSPENDED-LICENSE',
            'status' => 'suspended',
            'allowed_devices' => 2,
            'expires_at' => now()->addYear(),
        ]);

        $response = $this->postJson('/api/device/activate', [
            'license_key' => 'SUSPENDED-LICENSE',
            'device_id' => 'PC005',
            'device_name' => 'Test Device 5',
        ]);

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'License suspended',
            ]);

        // Verify activation log was created
        $this->assertDatabaseHas('activation_logs', [
            'license_key' => 'SUSPENDED-LICENSE',
            'device_id' => 'PC005',
            'success' => false,
            'message' => 'License suspended',
        ]);
    }

    /**
     * Test 6: تم الوصول للحد الأقصى → Failure, Device Limit Reached
     */
    public function test_device_limit_reached_failure(): void
    {
        // Create a user and an active license with limit of 2
        $user = User::factory()->create(['role' => 'admin']);
        $license = License::factory()->create([
            'user_id' => $user->id,
            'license_key' => 'LIMIT-LICENSE',
            'status' => 'active',
            'allowed_devices' => 2,
            'expires_at' => now()->addYear(),
        ]);

        // Create 2 active devices
        Device::factory()->create([
            'license_id' => $license->id,
            'device_id' => 'PC006',
            'status' => 'active',
        ]);
        Device::factory()->create([
            'license_id' => $license->id,
            'device_id' => 'PC007',
            'status' => 'active',
        ]);

        // Try to activate a 3rd device
        $response = $this->postJson('/api/device/activate', [
            'license_key' => 'LIMIT-LICENSE',
            'device_id' => 'PC008',
            'device_name' => 'Test Device 8',
        ]);

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Device limit reached',
                'allowed_devices' => 2,
            ]);

        // Verify activation log was created
        $this->assertDatabaseHas('activation_logs', [
            'license_key' => 'LIMIT-LICENSE',
            'device_id' => 'PC008',
            'success' => false,
            'message' => 'Device limit reached',
        ]);
    }

    /**
     * Test 7: يوجد جهاز Revoked + تفعيل جهاز جديد → Success
     * لأن الجهاز الملغى لا يجب أن يستهلك مقعدًا من الترخيص.
     */
    public function test_revoked_device_does_not_count_towards_limit(): void
    {
        // Create a user and an active license with limit of 2
        $user = User::factory()->create(['role' => 'admin']);
        $license = License::factory()->create([
            'user_id' => $user->id,
            'license_key' => 'REVOKED-LICENSE',
            'status' => 'active',
            'allowed_devices' => 2,
            'expires_at' => now()->addYear(),
        ]);

        // Create 1 active device and 1 revoked device
        Device::factory()->create([
            'license_id' => $license->id,
            'device_id' => 'PC009',
            'status' => 'active',
        ]);
        Device::factory()->create([
            'license_id' => $license->id,
            'device_id' => 'PC010',
            'status' => 'revoked',
        ]);

        // Try to activate a new device (should succeed because only 1 active device exists)
        $response = $this->postJson('/api/device/activate', [
            'license_key' => 'REVOKED-LICENSE',
            'device_id' => 'PC011',
            'device_name' => 'Test Device 11',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Device activated successfully',
                'license_key' => 'REVOKED-LICENSE',
                'device_id' => 'PC011',
                'is_known_device' => false,
            ]);

        // Verify device was created
        $this->assertDatabaseHas('devices', [
            'device_id' => 'PC011',
            'license_id' => $license->id,
            'status' => 'active',
        ]);

        // Verify activation log was created
        $this->assertDatabaseHas('activation_logs', [
            'license_key' => 'REVOKED-LICENSE',
            'device_id' => 'PC011',
            'success' => true,
            'message' => 'New device activated',
        ]);
    }
}
