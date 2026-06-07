<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;

class HeartbeatController extends Controller
{
    /**
     * Receive heartbeat from a device.
     * 
     * This is a simple heartbeat mechanism that:
     * - Updates the device's last_seen timestamp
     * - Verifies the device exists and is active
     * - Does NOT create devices, check limits, or perform activation
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function heartbeat(Request $request)
    {
        // Validate the request - only device_id required for demo
        $validated = $request->validate([
            'device_id' => ['required', 'string', 'max:100'],
        ]);

        $deviceId = $validated['device_id'];

        // STEP 1: Find the device
        $device = Device::where('device_id', $deviceId)->first();

        if (!$device) {
            return response()->json([
                'success' => false,
                'message' => 'Device not found',
            ], 404);
        }

        // STEP 2: Verify device is active (not suspended or revoked)
        if (!$device->isActive()) {
            $message = match($device->status) {
                'suspended' => 'Device suspended',
                'revoked' => 'Device revoked',
                default => 'Device not active',
            };

            return response()->json([
                'success' => false,
                'message' => $message,
            ], 403);
        }

        // STEP 3: Update last_seen timestamp ONLY
        // No other fields are modified
        $device->updateLastSeen();

        // STEP 4: Return success
        return response()->json([
            'success' => true,
            'message' => 'Heartbeat received',
            'device_id' => $deviceId,
            'last_seen' => $device->last_seen,
        ]);
    }
}
