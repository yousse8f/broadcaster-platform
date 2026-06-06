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

        // Filter by license
        if ($request->filled('license_id')) {
            $query->where('license_id', $request->license_id);
        }

        // Filter by client (user)
        if ($request->filled('user_id')) {
            $query->whereHas('license', function ($q) use ($request) {
                $q->where('user_id', $request->user_id);
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $devices = $query->latest()->get();

        // Get statistics
        $statistics = [
            'total' => Device::count(),
            'active' => Device::where('status', 'active')->count(),
            'suspended' => Device::where('status', 'suspended')->count(),
            'revoked' => Device::where('status', 'revoked')->count(),
            'online' => Device::online()->count(),
        ];

        // Get filter options
        $licenses = \App\Models\License::with('user')->get();
        $clients = \App\Models\User::where('role', 'client')->get();

        return view('devices.index', compact('devices', 'statistics', 'licenses', 'clients'));
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
