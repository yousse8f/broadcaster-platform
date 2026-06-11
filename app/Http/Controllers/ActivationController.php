<?php

namespace App\Http\Controllers;

use App\Mail\NewDeviceActivated;
use App\Models\ActivationLog;
use App\Models\Device;
use App\Models\License;
use App\Models\SecurityAuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ActivationController extends Controller
{
    /**
     * Activate a device with a license key.
     * 
     * This is the core licensing activation logic that handles:
     * - License validation
     * - Device limit checking (only active devices count)
     * - Known device detection
     * - Comprehensive logging
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function activate(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'license_key' => ['required', 'string', 'max:100'],
            'device_id' => ['required', 'string', 'max:100'],
            'device_name' => ['nullable', 'string', 'max:255'],
            'operating_system' => ['nullable', 'string', 'max:50'],
            'app_version' => ['nullable', 'string', 'max:50'],
        ]);

        $licenseKey = $validated['license_key'];
        $deviceId = $validated['device_id'];
        $deviceName = $validated['device_name'] ?? 'Unknown Device';
        $operatingSystem = $validated['operating_system'] ?? null;
        $ipAddress = $request->ip();

        // STEP 1: Check if license exists
        $license = License::where('license_key', $licenseKey)->first();

        if (!$license) {
            $this->logActivation(
                $licenseKey,
                $deviceId,
                $deviceName,
                $ipAddress,
                false,
                'License not found',
                null
            );

            // Log security event
            SecurityAuditLog::logEvent([
                'event_type' => 'failed_activation',
                'ip_address' => $ipAddress,
                'user_agent' => $request->userAgent(),
                'license_key' => $licenseKey,
                'device_id' => $deviceId,
                'details' => [
                    'device_name' => $deviceName,
                    'reason' => 'License not found',
                ],
                'severity' => 'high',
            ]);

            return response()->json([
                'success' => false,
                'message' => 'License not found',
            ], 404);
        }

        // STEP 2: Check if license is active
        if ($license->status !== 'active') {
            $message = match($license->status) {
                'suspended' => 'License suspended',
                'expired' => 'License expired',
                'inactive' => 'License inactive',
                default => 'License not active',
            };

            $this->logActivation(
                $licenseKey,
                $deviceId,
                $deviceName,
                $ipAddress,
                false,
                $message,
                $license->id
            );

            // Log security event
            SecurityAuditLog::logEvent([
                'event_type' => 'failed_activation',
                'ip_address' => $ipAddress,
                'user_agent' => $request->userAgent(),
                'license_key' => $licenseKey,
                'device_id' => $deviceId,
                'details' => [
                    'device_name' => $deviceName,
                    'reason' => $message,
                ],
                'severity' => 'medium',
            ]);

            return response()->json([
                'success' => false,
                'message' => $message,
            ], 403);
        }

        // STEP 3: Check if license is not expired
        if ($license->expires_at && $license->expires_at->isPast()) {
            $this->logActivation(
                $licenseKey,
                $deviceId,
                $deviceName,
                $ipAddress,
                false,
                'License expired',
                $license->id
            );

            // Log security event
            SecurityAuditLog::logEvent([
                'event_type' => 'failed_activation',
                'ip_address' => $ipAddress,
                'user_agent' => $request->userAgent(),
                'license_key' => $licenseKey,
                'device_id' => $deviceId,
                'details' => [
                    'device_name' => $deviceName,
                    'reason' => 'License expired',
                ],
                'severity' => 'medium',
            ]);

            return response()->json([
                'success' => false,
                'message' => 'License expired',
            ], 403);
        }

        // STEP 4: Check for existing device
        $existingDevice = Device::where('device_id', $deviceId)
            ->where('license_id', $license->id)
            ->first();

        if ($existingDevice) {
            // CASE 1: Device already exists
            // Check if device is revoked
            if ($existingDevice->status === 'revoked') {
                $this->logActivation(
                    $licenseKey,
                    $deviceId,
                    $deviceName,
                    $ipAddress,
                    false,
                    'Device revoked',
                    $license->id
                );

                return response()->json([
                    'success' => false,
                    'message' => 'Device revoked',
                ], 403);
            }

            // Update last_seen for known device
            $existingDevice->updateLastSeen();

            $this->logActivation(
                $licenseKey,
                $deviceId,
                $deviceName,
                $ipAddress,
                true,
                'Known device activated',
                $license->id
            );

            $activeDevicesCount = $license->devices()->active()->count();

            return response()->json([
                'success' => true,
                'message' => 'Known device activated',
                'license_key' => $licenseKey,
                'device_id' => $deviceId,
                'device_name' => $existingDevice->device_name,
                'allowed_devices' => $license->allowed_devices,
                'registered_devices' => $activeDevicesCount,
                'activated_at' => $existingDevice->first_activated_at,
                'is_known_device' => true,
            ]);
        }

        // CASE 2 & 3: New device
        // Count ONLY active devices (revoked/suspended don't count)
        $activeDevicesCount = $license->devices()->active()->count();

        // Check device limit
        if ($activeDevicesCount >= $license->allowed_devices) {
            $this->logActivation(
                $licenseKey,
                $deviceId,
                $deviceName,
                $ipAddress,
                false,
                'Device limit reached',
                $license->id
            );

            // Log security event
            SecurityAuditLog::logEvent([
                'event_type' => 'failed_activation',
                'ip_address' => $ipAddress,
                'user_agent' => $request->userAgent(),
                'license_key' => $licenseKey,
                'device_id' => $deviceId,
                'details' => [
                    'device_name' => $deviceName,
                    'reason' => 'Device limit reached',
                    'allowed_devices' => $license->allowed_devices,
                    'active_devices' => $activeDevicesCount,
                ],
                'severity' => 'low',
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Device limit reached',
                'allowed_devices' => $license->allowed_devices,
                'registered_devices' => $activeDevicesCount,
            ], 403);
        }

        // Create new device
        $newDevice = Device::create([
            'license_id' => $license->id,
            'device_id' => $deviceId,
            'device_name' => $deviceName,
            'operating_system' => $operatingSystem,
            'ip_address' => $ipAddress,
            'status' => 'active',
            'first_activated_at' => now(),
            'last_seen' => now(),
        ]);

        // Send email notification for new device activation
        try {
            Mail::to($license->user->email)->send(new NewDeviceActivated($newDevice));
        } catch (\Exception $e) {
            Log::error("Failed to send new device activation email: " . $e->getMessage());
        }

        $this->logActivation(
            $licenseKey,
            $deviceId,
            $deviceName,
            $ipAddress,
            true,
            'New device activated',
            $license->id
        );

        $newActiveDevicesCount = $license->devices()->active()->count();

        return response()->json([
            'success' => true,
            'message' => 'Device activated successfully',
            'license_key' => $licenseKey,
            'device_id' => $deviceId,
            'device_name' => $deviceName,
            'allowed_devices' => $license->allowed_devices,
            'registered_devices' => $newActiveDevicesCount,
            'activated_at' => $newDevice->first_activated_at,
            'is_known_device' => false,
        ]);
    }

    /**
     * Log activation attempt to activation_logs table.
     * 
     * @param string $licenseKey
     * @param string $deviceId
     * @param string $deviceName
     * @param string|null $ipAddress
     * @param bool $success
     * @param string $message
     * @param int|null $licenseId
     * @return void
     */
    private function logActivation(
        string $licenseKey,
        string $deviceId,
        string $deviceName,
        ?string $ipAddress,
        bool $success,
        string $message,
        ?int $licenseId
    ): void {
        ActivationLog::create([
            'license_key' => $licenseKey,
            'device_id' => $deviceId,
            'device_name' => $deviceName,
            'ip_address' => $ipAddress,
            'success' => $success,
            'message' => $message,
            'license_id' => $licenseId,
        ]);
    }
}
