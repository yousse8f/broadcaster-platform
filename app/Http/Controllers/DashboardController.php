<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function adminDashboard(): View
    {
        // Show system-wide statistics for admin
        $licensesCount = \App\Models\License::count();
        $usersCount = \App\Models\User::count();
        $devicesCount = \App\Models\Device::count();

        return view('admin-dashboard', [
            'licensesCount' => $licensesCount,
            'usersCount' => $usersCount,
            'devicesCount' => $devicesCount,
        ]);
    }

    /**
     * Display the client dashboard.
     */
    public function clientDashboard(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Show user-specific statistics for client
        $licensesCount = $user->licenses()->count();
        $usersCount = 1; // Only current user
        // Get devices through user's licenses
        $devicesCount = \App\Models\Device::whereIn('license_id', $user->licenses()->pluck('id'))->count();

        return view('client-dashboard', [
            'licensesCount' => $licensesCount,
            'usersCount' => $usersCount,
            'devicesCount' => $devicesCount,
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
