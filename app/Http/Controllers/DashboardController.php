<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(): View
    {
        return view('dashboard', [
            'licensesCount' => \App\Models\License::count(),
            'usersCount' => \App\Models\User::count(),
            'devicesCount' => \App\Models\Device::count(),
        ]);
    }

    /**
     * Display activation logs.
     */
    public function activationLogs(Request $request): View
    {
        $query = \App\Models\ActivationLog::with(['license'])->latest();

        // Filter by success/failure
        if ($request->filled('status')) {
            if ($request->status === 'success') {
                $query->successful();
            } elseif ($request->status === 'failed') {
                $query->failed();
            }
        }

        // Filter by license key
        if ($request->filled('license_key')) {
            $query->byLicenseKey($request->license_key);
        }

        // Filter by device ID
        if ($request->filled('device_id')) {
            $query->byDeviceId($request->device_id);
        }

        $activationLogs = $query->paginate(50);

        // Get statistics
        $totalCount = \App\Models\ActivationLog::count();
        $successCount = \App\Models\ActivationLog::successful()->count();
        $failedCount = \App\Models\ActivationLog::failed()->count();

        return view('activation-logs', compact(
            'activationLogs',
            'totalCount',
            'successCount',
            'failedCount'
        ));
    }
}
