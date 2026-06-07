<?php

namespace Tests\Feature;

use App\Models\ActivationLog;
use App\Models\Device;
use App\Models\License;
use App\Models\LicenseValidationLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelsTest extends TestCase
{
    use RefreshDatabase;

    // ==================== USER MODEL TESTS ====================

    /**
     * Test creating a user
     */
    public function test_create_user(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'role' => 'admin',
        ]);

        $this->assertEquals('Test User', $user->name);
    }

    /**
     * Test updating a user
     */
    public function test_update_user(): void
    {
        $user = User::factory()->create(['name' => 'Old Name']);
        $user->update(['name' => 'New Name']);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New Name',
        ]);
    }

    /**
     * Test deleting a user
     */
    public function test_delete_user(): void
    {
        $user = User::factory()->create();
        $userId = $user->id;

        $user->delete();

        $this->assertDatabaseMissing('users', ['id' => $userId]);
    }

    /**
     * Test user has many licenses relationship
     */
    public function test_user_has_many_licenses(): void
    {
        $user = User::factory()->create();
        $license1 = License::factory()->create(['user_id' => $user->id]);
        $license2 = License::factory()->create(['user_id' => $user->id]);

        $this->assertCount(2, $user->licenses()->get());
        $this->assertEquals($license1->id, $user->licenses()->first()->id);
        $this->assertEquals($license2->id, $user->licenses()->get()->last()->id);
    }

    /**
     * Test user password is hashed
     */
    public function test_user_password_is_hashed(): void
    {
        $user = User::factory()->create(['password' => 'plain_password']);

        $this->assertNotEquals('plain_password', $user->password);
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('plain_password', $user->password));
    }

    // ==================== LICENSE MODEL TESTS ====================

    /**
     * Test creating a license
     */
    public function test_create_license(): void
    {
        $user = User::factory()->create();
        $license = License::create([
            'user_id' => $user->id,
            'license_key' => 'TEST-LICENSE-001',
            'status' => 'active',
            'expires_at' => now()->addYear(),
            'allowed_devices' => 3,
        ]);

        $this->assertDatabaseHas('licenses', [
            'license_key' => 'TEST-LICENSE-001',
            'status' => 'active',
        ]);
    }

    /**
     * Test updating a license
     */
    public function test_update_license(): void
    {
        $license = License::factory()->create(['status' => 'active']);
        $license->update(['status' => 'suspended']);

        $this->assertDatabaseHas('licenses', [
            'id' => $license->id,
            'status' => 'suspended',
        ]);
    }

    /**
     * Test deleting a license
     */
    public function test_delete_license(): void
    {
        $license = License::factory()->create();
        $licenseId = $license->id;

        $license->delete();

        $this->assertDatabaseMissing('licenses', ['id' => $licenseId]);
    }

    /**
     * Test license belongs to user relationship
     */
    public function test_license_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $license = License::factory()->create(['user_id' => $user->id]);

        $this->assertEquals($user->id, $license->user->id);
        $this->assertEquals($user->name, $license->user->name);
    }

    /**
     * Test license has many devices relationship
     */
    public function test_license_has_many_devices(): void
    {
        $license = License::factory()->create();
        $device1 = Device::factory()->create(['license_id' => $license->id]);
        $device2 = Device::factory()->create(['license_id' => $license->id]);

        $this->assertCount(2, $license->devices()->get());
    }

    /**
     * Test license isActive method with active license
     */
    public function test_license_is_active_with_active_license(): void
    {
        $license = License::factory()->create([
            'status' => 'active',
            'expires_at' => now()->addYear(),
        ]);

        $this->assertTrue($license->isActive());
    }

    /**
     * Test license isActive method with expired license
     */
    public function test_license_is_active_with_expired_license(): void
    {
        $license = License::factory()->create([
            'status' => 'active',
            'expires_at' => now()->subDay(),
        ]);

        $this->assertFalse($license->isActive());
    }

    /**
     * Test license isActive method with suspended license
     */
    public function test_license_is_active_with_suspended_license(): void
    {
        $license = License::factory()->create([
            'status' => 'suspended',
            'expires_at' => now()->addYear(),
        ]);

        $this->assertFalse($license->isActive());
    }

    /**
     * Test license hasReachedDeviceLimit method
     */
    public function test_license_has_reached_device_limit(): void
    {
        $license = License::factory()->create(['allowed_devices' => 2]);

        // Create 1 active device (should not reach limit)
        Device::factory()->create([
            'license_id' => $license->id,
            'status' => 'active',
        ]);
        $this->assertFalse($license->hasReachedDeviceLimit());

        // Create 2nd active device (should reach limit)
        Device::factory()->create([
            'license_id' => $license->id,
            'status' => 'active',
        ]);
        $this->assertTrue($license->hasReachedDeviceLimit());
    }

    /**
     * Test license getActiveDevicesCount attribute
     */
    public function test_license_get_active_devices_count(): void
    {
        $license = License::factory()->create();
        Device::factory()->create(['license_id' => $license->id, 'status' => 'active']);
        Device::factory()->create(['license_id' => $license->id, 'status' => 'active']);
        Device::factory()->create(['license_id' => $license->id, 'status' => 'suspended']);

        $this->assertEquals(2, $license->active_devices_count);
    }

    /**
     * Test license getOnlineDevicesCount attribute
     */
    public function test_license_get_online_devices_count(): void
    {
        $license = License::factory()->create();
        Device::factory()->create([
            'license_id' => $license->id,
            'status' => 'active',
            'last_seen' => now()->subMinutes(2),
        ]);
        Device::factory()->create([
            'license_id' => $license->id,
            'status' => 'active',
            'last_seen' => now()->subMinutes(10),
        ]);

        $this->assertEquals(1, $license->online_devices_count);
    }

    // ==================== DEVICE MODEL TESTS ====================

    /**
     * Test creating a device
     */
    public function test_create_device(): void
    {
        $license = License::factory()->create();
        $device = Device::create([
            'license_id' => $license->id,
            'device_id' => 'DEV-001',
            'device_name' => 'Test Device',
            'status' => 'active',
            'operating_system' => 'windows',
        ]);

        $this->assertDatabaseHas('devices', [
            'device_id' => 'DEV-001',
            'license_id' => $license->id,
        ]);
    }

    /**
     * Test updating a device
     */
    public function test_update_device(): void
    {
        $device = Device::factory()->create(['device_name' => 'Old Name']);
        $device->update(['device_name' => 'New Name']);

        $this->assertDatabaseHas('devices', [
            'id' => $device->id,
            'device_name' => 'New Name',
        ]);
    }

    /**
     * Test deleting a device
     */
    public function test_delete_device(): void
    {
        $device = Device::factory()->create();
        $deviceId = $device->id;

        $device->delete();

        $this->assertDatabaseMissing('devices', ['id' => $deviceId]);
    }

    /**
     * Test device belongs to license relationship
     */
    public function test_device_belongs_to_license(): void
    {
        $license = License::factory()->create();
        $device = Device::factory()->create(['license_id' => $license->id]);

        $this->assertEquals($license->id, $device->license->id);
    }

    /**
     * Test device user relationship through license
     */
    public function test_device_user_relationship_through_license(): void
    {
        $user = User::factory()->create();
        $license = License::factory()->create(['user_id' => $user->id]);
        $device = Device::factory()->create(['license_id' => $license->id]);

        // Access user through license relationship
        $this->assertEquals($user->id, $device->license->user->id);
    }

    /**
     * Test device updateLastSeen method
     */
    public function test_device_update_last_seen(): void
    {
        $device = Device::factory()->create(['last_seen' => now()->subHour()]);

        $device->updateLastSeen();

        $this->assertGreaterThan(
            now()->subMinute(),
            $device->fresh()->last_seen
        );
    }

    /**
     * Test device isOnline method with online device
     */
    public function test_device_is_online_with_online_device(): void
    {
        $device = Device::factory()->create([
            'last_seen' => now()->subMinutes(2),
        ]);

        $this->assertTrue($device->isOnline());
    }

    /**
     * Test device isOnline method with offline device
     */
    public function test_device_is_online_with_offline_device(): void
    {
        $device = Device::factory()->create([
            'last_seen' => now()->subMinutes(10),
        ]);

        $this->assertFalse($device->isOnline());
    }

    /**
     * Test device isActive method
     */
    public function test_device_is_active(): void
    {
        $device = Device::factory()->create(['status' => 'active']);
        $this->assertTrue($device->isActive());

        $device->update(['status' => 'suspended']);
        $this->assertFalse($device->isActive());
    }

    /**
     * Test device isSuspended method
     */
    public function test_device_is_suspended(): void
    {
        $device = Device::factory()->create(['status' => 'suspended']);
        $this->assertTrue($device->isSuspended());

        $device->update(['status' => 'active']);
        $this->assertFalse($device->isSuspended());
    }

    /**
     * Test device isRevoked method
     */
    public function test_device_is_revoked(): void
    {
        $device = Device::factory()->create(['status' => 'revoked']);
        $this->assertTrue($device->isRevoked());

        $device->update(['status' => 'active']);
        $this->assertFalse($device->isRevoked());
    }

    /**
     * Test device getOnlineStatus attribute
     */
    public function test_device_get_online_status_attribute(): void
    {
        $onlineDevice = Device::factory()->create([
            'last_seen' => now()->subMinutes(2),
        ]);
        $this->assertEquals('Online', $onlineDevice->online_status);

        $offlineDevice = Device::factory()->create([
            'last_seen' => now()->subMinutes(10),
        ]);
        $this->assertEquals('Offline', $offlineDevice->online_status);
    }

    /**
     * Test device getStatusBadgeClass attribute
     */
    public function test_device_get_status_badge_class_attribute(): void
    {
        $activeDevice = Device::factory()->create(['status' => 'active']);
        $this->assertEquals('status-active', $activeDevice->status_badge_class);

        $suspendedDevice = Device::factory()->create(['status' => 'suspended']);
        $this->assertEquals('status-suspended', $suspendedDevice->status_badge_class);

        $revokedDevice = Device::factory()->create(['status' => 'revoked']);
        $this->assertEquals('status-revoked', $revokedDevice->status_badge_class);
    }

    /**
     * Test device activate method
     */
    public function test_device_activate(): void
    {
        $device = Device::factory()->create(['status' => 'suspended']);
        $device->activate();

        $this->assertEquals('active', $device->fresh()->status);
    }

    /**
     * Test device suspend method
     */
    public function test_device_suspend(): void
    {
        $device = Device::factory()->create(['status' => 'active']);
        $device->suspend();

        $this->assertEquals('suspended', $device->fresh()->status);
    }

    /**
     * Test device revoke method
     */
    public function test_device_revoke(): void
    {
        $device = Device::factory()->create(['status' => 'active']);
        $device->revoke();

        $this->assertEquals('revoked', $device->fresh()->status);
    }

    /**
     * Test device active scope
     */
    public function test_device_active_scope(): void
    {
        Device::factory()->create(['status' => 'active']);
        Device::factory()->create(['status' => 'suspended']);
        Device::factory()->create(['status' => 'active']);

        $activeDevices = Device::active()->get();
        $this->assertCount(2, $activeDevices);
    }

    /**
     * Test device suspended scope
     */
    public function test_device_suspended_scope(): void
    {
        Device::factory()->create(['status' => 'active']);
        Device::factory()->create(['status' => 'suspended']);
        Device::factory()->create(['status' => 'suspended']);

        $suspendedDevices = Device::suspended()->get();
        $this->assertCount(2, $suspendedDevices);
    }

    /**
     * Test device revoked scope
     */
    public function test_device_revoked_scope(): void
    {
        Device::factory()->create(['status' => 'active']);
        Device::factory()->create(['status' => 'revoked']);
        Device::factory()->create(['status' => 'revoked']);

        $revokedDevices = Device::revoked()->get();
        $this->assertCount(2, $revokedDevices);
    }

    /**
     * Test device online scope
     */
    public function test_device_online_scope(): void
    {
        Device::factory()->create(['last_seen' => now()->subMinutes(2)]);
        Device::factory()->create(['last_seen' => now()->subMinutes(10)]);
        Device::factory()->create(['last_seen' => now()->subMinutes(3)]);

        $onlineDevices = Device::online()->get();
        $this->assertCount(2, $onlineDevices);
    }

    /**
     * Test device offline scope
     */
    public function test_device_offline_scope(): void
    {
        Device::factory()->create(['last_seen' => now()->subMinutes(2)]);
        Device::factory()->create(['last_seen' => now()->subMinutes(10)]);
        Device::factory()->create(['last_seen' => null]);

        $offlineDevices = Device::offline()->get();
        $this->assertCount(2, $offlineDevices);
    }

    // ==================== ACTIVATION LOG MODEL TESTS ====================

    /**
     * Test creating an activation log
     */
    public function test_create_activation_log(): void
    {
        $license = License::factory()->create();
        $activationLog = ActivationLog::create([
            'license_key' => 'TEST-LICENSE',
            'device_id' => 'DEV-001',
            'device_name' => 'Test Device',
            'ip_address' => '192.168.1.1',
            'success' => true,
            'message' => 'Device activated',
            'license_id' => $license->id,
        ]);

        $this->assertDatabaseHas('activation_logs', [
            'license_key' => 'TEST-LICENSE',
            'device_id' => 'DEV-001',
            'success' => true,
        ]);
    }

    /**
     * Test updating an activation log
     */
    public function test_update_activation_log(): void
    {
        $license = License::factory()->create();
        $activationLog = ActivationLog::create([
            'license_key' => 'TEST-LICENSE',
            'device_id' => 'DEV-001',
            'device_name' => 'Test Device',
            'ip_address' => '192.168.1.1',
            'success' => true,
            'message' => 'Old message',
            'license_id' => $license->id,
        ]);
        $activationLog->update(['message' => 'New message']);

        $this->assertDatabaseHas('activation_logs', [
            'id' => $activationLog->id,
            'message' => 'New message',
        ]);
    }

    /**
     * Test deleting an activation log
     */
    public function test_delete_activation_log(): void
    {
        $license = License::factory()->create();
        $activationLog = ActivationLog::create([
            'license_key' => 'TEST-LICENSE',
            'device_id' => 'DEV-001',
            'device_name' => 'Test Device',
            'ip_address' => '192.168.1.1',
            'success' => true,
            'message' => 'Device activated',
            'license_id' => $license->id,
        ]);
        $logId = $activationLog->id;

        $activationLog->delete();

        $this->assertDatabaseMissing('activation_logs', ['id' => $logId]);
    }

    /**
     * Test activation log belongs to license relationship
     */
    public function test_activation_log_belongs_to_license(): void
    {
        $license = License::factory()->create();
        $activationLog = ActivationLog::create([
            'license_key' => 'TEST-LICENSE',
            'device_id' => 'DEV-001',
            'device_name' => 'Test Device',
            'ip_address' => '192.168.1.1',
            'success' => true,
            'message' => 'Device activated',
            'license_id' => $license->id,
        ]);

        $this->assertEquals($license->id, $activationLog->license->id);
    }

    /**
     * Test activation log successful scope
     */
    public function test_activation_log_successful_scope(): void
    {
        $license = License::factory()->create();
        ActivationLog::create([
            'license_key' => 'TEST-LICENSE',
            'device_id' => 'DEV-001',
            'success' => true,
            'message' => 'Success',
            'license_id' => $license->id,
        ]);
        ActivationLog::create([
            'license_key' => 'TEST-LICENSE',
            'device_id' => 'DEV-002',
            'success' => false,
            'message' => 'Failed',
            'license_id' => $license->id,
        ]);
        ActivationLog::create([
            'license_key' => 'TEST-LICENSE',
            'device_id' => 'DEV-003',
            'success' => true,
            'message' => 'Success',
            'license_id' => $license->id,
        ]);

        $successfulLogs = ActivationLog::successful()->get();
        $this->assertCount(2, $successfulLogs);
    }

    /**
     * Test activation log failed scope
     */
    public function test_activation_log_failed_scope(): void
    {
        $license = License::factory()->create();
        ActivationLog::create([
            'license_key' => 'TEST-LICENSE',
            'device_id' => 'DEV-001',
            'success' => true,
            'message' => 'Success',
            'license_id' => $license->id,
        ]);
        ActivationLog::create([
            'license_key' => 'TEST-LICENSE',
            'device_id' => 'DEV-002',
            'success' => false,
            'message' => 'Failed',
            'license_id' => $license->id,
        ]);
        ActivationLog::create([
            'license_key' => 'TEST-LICENSE',
            'device_id' => 'DEV-003',
            'success' => false,
            'message' => 'Failed',
            'license_id' => $license->id,
        ]);

        $failedLogs = ActivationLog::failed()->get();
        $this->assertCount(2, $failedLogs);
    }

    /**
     * Test activation log byLicenseKey scope
     */
    public function test_activation_log_by_license_key_scope(): void
    {
        $license = License::factory()->create();
        ActivationLog::create([
            'license_key' => 'LICENSE-001',
            'device_id' => 'DEV-001',
            'success' => true,
            'message' => 'Success',
            'license_id' => $license->id,
        ]);
        ActivationLog::create([
            'license_key' => 'LICENSE-002',
            'device_id' => 'DEV-002',
            'success' => true,
            'message' => 'Success',
            'license_id' => $license->id,
        ]);
        ActivationLog::create([
            'license_key' => 'LICENSE-001',
            'device_id' => 'DEV-003',
            'success' => true,
            'message' => 'Success',
            'license_id' => $license->id,
        ]);

        $logs = ActivationLog::byLicenseKey('LICENSE-001')->get();
        $this->assertCount(2, $logs);
    }

    /**
     * Test activation log byDeviceId scope
     */
    public function test_activation_log_by_device_id_scope(): void
    {
        $license = License::factory()->create();
        ActivationLog::create([
            'license_key' => 'TEST-LICENSE',
            'device_id' => 'DEV-001',
            'success' => true,
            'message' => 'Success',
            'license_id' => $license->id,
        ]);
        ActivationLog::create([
            'license_key' => 'TEST-LICENSE',
            'device_id' => 'DEV-002',
            'success' => true,
            'message' => 'Success',
            'license_id' => $license->id,
        ]);
        ActivationLog::create([
            'license_key' => 'TEST-LICENSE',
            'device_id' => 'DEV-001',
            'success' => true,
            'message' => 'Success',
            'license_id' => $license->id,
        ]);

        $logs = ActivationLog::byDeviceId('DEV-001')->get();
        $this->assertCount(2, $logs);
    }

    // ==================== LICENSE VALIDATION LOG MODEL TESTS ====================

    /**
     * Test creating a license validation log
     */
    public function test_create_license_validation_log(): void
    {
        $license = License::factory()->create();
        $validationLog = LicenseValidationLog::create([
            'license_key' => 'TEST-LICENSE',
            'ip_address' => '192.168.1.1',
            'result' => 'success',
            'message' => 'License is valid',
            'license_id' => $license->id,
        ]);

        $this->assertDatabaseHas('license_validation_logs', [
            'license_key' => 'TEST-LICENSE',
            'result' => 'success',
        ]);
    }

    /**
     * Test updating a license validation log
     */
    public function test_update_license_validation_log(): void
    {
        $license = License::factory()->create();
        $validationLog = LicenseValidationLog::create([
            'license_key' => 'TEST-LICENSE',
            'ip_address' => '192.168.1.1',
            'result' => 'success',
            'message' => 'Old message',
            'license_id' => $license->id,
        ]);
        $validationLog->update(['message' => 'New message']);

        $this->assertDatabaseHas('license_validation_logs', [
            'id' => $validationLog->id,
            'message' => 'New message',
        ]);
    }

    /**
     * Test deleting a license validation log
     */
    public function test_delete_license_validation_log(): void
    {
        $license = License::factory()->create();
        $validationLog = LicenseValidationLog::create([
            'license_key' => 'TEST-LICENSE',
            'ip_address' => '192.168.1.1',
            'result' => 'success',
            'message' => 'License is valid',
            'license_id' => $license->id,
        ]);
        $logId = $validationLog->id;

        $validationLog->delete();

        $this->assertDatabaseMissing('license_validation_logs', ['id' => $logId]);
    }

    /**
     * Test license validation log belongs to license relationship
     */
    public function test_license_validation_log_belongs_to_license(): void
    {
        $license = License::factory()->create();
        $validationLog = LicenseValidationLog::create([
            'license_key' => 'TEST-LICENSE',
            'ip_address' => '192.168.1.1',
            'result' => 'success',
            'message' => 'License is valid',
            'license_id' => $license->id,
        ]);

        $this->assertEquals($license->id, $validationLog->license->id);
    }

    // ==================== INTEGRATION TESTS ====================

    /**
     * Test complete workflow: User -> License -> Device -> Activation Log
     */
    public function test_complete_workflow(): void
    {
        // Create user
        $user = User::factory()->create(['role' => 'admin']);

        // Create license for user
        $license = License::create([
            'user_id' => $user->id,
            'license_key' => 'WORKFLOW-LICENSE',
            'status' => 'active',
            'expires_at' => now()->addYear(),
            'allowed_devices' => 2,
        ]);

        // Create device for license
        $device = Device::create([
            'license_id' => $license->id,
            'device_id' => 'WORKFLOW-DEV-001',
            'device_name' => 'Workflow Device',
            'status' => 'active',
            'operating_system' => 'windows',
        ]);

        // Create activation log
        $activationLog = ActivationLog::create([
            'license_key' => 'WORKFLOW-LICENSE',
            'device_id' => 'WORKFLOW-DEV-001',
            'device_name' => 'Workflow Device',
            'ip_address' => '192.168.1.1',
            'success' => true,
            'message' => 'Device activated successfully',
            'license_id' => $license->id,
        ]);

        // Verify relationships
        $this->assertEquals($user->id, $license->user->id);
        $this->assertEquals($license->id, $device->license->id);
        $this->assertEquals($license->id, $activationLog->license->id);
        $this->assertEquals(1, $user->licenses()->count());
        $this->assertEquals(1, $license->devices()->count());
        $this->assertEquals($user->id, $device->license->user->id);

        // Verify device methods
        $device->updateLastSeen();
        $this->assertTrue($device->fresh()->isOnline());
        $this->assertTrue($device->isActive());

        // Verify license methods
        $this->assertTrue($license->isActive());
        $this->assertFalse($license->hasReachedDeviceLimit());
        $this->assertEquals(1, $license->active_devices_count);
    }
}
