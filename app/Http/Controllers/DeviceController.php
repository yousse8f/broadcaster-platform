<?php

namespace App\Http\Controllers;

use App\Models\AdminAuditLog;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Carbon\Carbon;

class DeviceController extends Controller
{
    /**
     * Display a listing of devices.
     */
    public function index(Request $request): View
    {
        $query = Device::with(['license.user']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by operating system
        if ($request->filled('os')) {
            $query->where('operating_system', $request->os);
        }

        // Search by device name or ID
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('device_name', 'like', "%{$search}%")
                  ->orWhere('device_id', 'like', "%{$search}%");
            });
        }

        $devices = $query->latest()->paginate(10);

        // Get statistics
        $devicesCount = Device::count();
        $onlineCount = Device::online()->count();
        $offlineCount = Device::offline()->count();
        $suspendedCount = Device::suspended()->count();

        return view('devices.index', compact(
            'devices',
            'devicesCount',
            'onlineCount',
            'offlineCount',
            'suspendedCount'
        ));
    }

    /**
     * Show the form for creating a new device.
     */
    public function create(): View
    {
        $licenses = \App\Models\License::with('user')->where('status', 'active')->get();
        return view('devices.create', compact('licenses'));
    }

    /**
     * Store a newly created device in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'device_name' => ['required', 'string', 'max:255'],
            'device_id' => ['required', 'string', 'max:255', 'unique:devices,device_id'],
            'license_id' => ['required', 'exists:licenses,id'],
            'operating_system' => ['required', 'in:windows,mac,linux'],
            'status' => ['required', 'in:active,suspended,revoked'],
        ]);

        Device::create($validated);

        return redirect()->route('admin.devices.index')
            ->with('success', 'Device created successfully.');
    }

    /**
     * Show the form for editing the specified device.
     */
    public function edit(Device $device): View
    {
        $licenses = \App\Models\License::with('user')->where('status', 'active')->get();
        return view('devices.edit', compact('device', 'licenses'));
    }

    /**
     * Update the specified device in storage.
     */
    public function update(Request $request, Device $device)
    {
        $validated = $request->validate([
            'device_name' => ['required', 'string', 'max:255'],
            'device_id' => ['required', 'string', 'max:255', 'unique:devices,device_id,' . $device->id],
            'license_id' => ['required', 'exists:licenses,id'],
            'operating_system' => ['required', 'in:windows,mac,linux'],
            'status' => ['required', 'in:active,suspended,revoked'],
        ]);

        $device->update($validated);

        return redirect()->route('admin.devices.index')
            ->with('success', 'Device updated successfully.');
    }

    /**
     * Remove the specified device from storage.
     */
    public function destroy(Device $device)
    {
        $device->delete();

        return redirect()->route('admin.devices.index')
            ->with('success', 'Device deleted successfully.');
    }

    /**
     * Display the specified device.
     */
    public function show(Device $device): View
    {
        $device->load(['license.user']);
        return view('devices.show', compact('device'));
    }

    /**
     * Activate the device.
     */
    public function activate(Device $device)
    {
        $device->activate();

        return redirect()->back()
            ->with('success', 'Device activated successfully.');
    }

    /**
     * Suspend the device.
     */
    public function suspend(Device $device)
    {
        $device->suspend();

        return redirect()->back()
            ->with('success', 'Device suspended successfully.');
    }

    /**
     * Revoke the device.
     */
    public function revoke(Device $device)
    {
        $oldStatus = $device->status;
        $device->revoke();

        // Log admin action
        AdminAuditLog::logAction([
            'admin_id' => Auth::id(),
            'action' => 'device_revoked',
            'entity_type' => 'device',
            'entity_id' => $device->id,
            'old_values' => ['status' => $oldStatus],
            'new_values' => ['status' => 'revoked'],
            'description' => "Revoked device {$device->device_name} ({$device->device_id})",
        ]);

        return redirect()->back()
            ->with('success', 'Device revoked successfully.');
    }

    /**
     * Display the authenticated user's devices.
     */
    public function myDevices(Request $request): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $query = Device::whereHas('license', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with('license');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by operating system
        if ($request->filled('os')) {
            $query->where('operating_system', $request->os);
        }

        $devices = $query->latest()->paginate(10);

        // Get statistics
        $devicesCount = $query->count();
        $onlineCount = (clone $query)->online()->count();
        $offlineCount = (clone $query)->offline()->count();
        $activeCount = (clone $query)->where('status', 'active')->count();

        return view('devices.my-devices', compact(
            'devices',
            'devicesCount',
            'onlineCount',
            'offlineCount',
            'activeCount'
        ));
    }

    /**
     * Display the specified device for client view.
     */
    public function clientShow(Device $device): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Security: Ensure user can only view their own devices
        if ($device->license->user_id !== $user->id) {
            abort(403, 'You do not have permission to view this device.');
        }

        $device->load(['license.user']);

        // Get activity logs for this device
        $activityLogs = \App\Models\AdminAuditLog::where('entity_type', 'device')
            ->where('entity_id', $device->id)
            ->latest()
            ->take(10)
            ->get();

        return view('devices.client-show', compact('device', 'activityLogs'));
    }

    /**
     * Revoke the device (client action).
     */
    public function clientRevoke(Device $device)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Security: Ensure user can only revoke their own devices
        if ($device->license->user_id !== $user->id) {
            abort(403, 'You do not have permission to revoke this device.');
        }

        $oldStatus = $device->status;
        $device->revoke();

        // Log client action
        \App\Models\AdminAuditLog::logAction([
            'admin_id' => $user->id, // Using user_id instead of admin_id for client actions
            'action' => 'device_revoked',
            'entity_type' => 'device',
            'entity_id' => $device->id,
            'old_values' => ['status' => $oldStatus],
            'new_values' => ['status' => 'revoked'],
            'description' => "Client revoked device {$device->device_name} ({$device->device_id})",
        ]);

        return redirect()->route('client.devices')
            ->with('success', 'Device removed successfully.');
    }

    /**
     * Get device information (public API for external applications).
     * Returns detailed device information including status and license details.
     */
    public function info(Request $request)
    {
        $validated = $request->validate([
            'device_id' => ['required', 'string', 'max:255'],
        ]);

        $device = Device::where('device_id', $validated['device_id'])
            ->with('license')
            ->first();

        if (!$device) {
            return response()->json([
                'success' => false,
                'message' => 'Device not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'device_id' => $device->device_id,
                'device_name' => $device->device_name,
                'os' => $device->operating_system,
                'status' => $device->status,
                'last_seen' => $device->last_seen ? $device->last_seen->toISOString() : null,
                'license_key' => $device->license ? $device->license->license_key : null,
            ],
        ]);
    }

    /**
     * Deactivate a device (public API for external applications).
     * This allows users to remove devices when they format their PC or get a new one.
     */
    public function deactivate(Request $request)
    {
        $validated = $request->validate([
            'device_id' => ['required', 'string', 'max:255'],
        ]);

        $device = Device::where('device_id', $validated['device_id'])->first();

        if (!$device) {
            return response()->json([
                'success' => false,
                'message' => 'Device not found',
            ], 404);
        }

        // Delete the device from the license
        $device->delete();

        return response()->json([
            'success' => true,
            'message' => 'Device deactivated successfully',
        ]);
    }
}
