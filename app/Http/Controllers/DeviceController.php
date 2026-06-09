<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
        $device->revoke();

        return redirect()->back()
            ->with('success', 'Device revoked successfully.');
    }
}
