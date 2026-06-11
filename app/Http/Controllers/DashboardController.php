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

        // Get user's licenses with device counts
        $licenses = $user->licenses()->with('devices')->get();

        // Statistics
        $activeLicensesCount = $licenses->where('status', 'active')->count();
        $totalDevicesCount = \App\Models\Device::whereIn('license_id', $licenses->pluck('id'))->count();
        $onlineDevicesCount = \App\Models\Device::whereIn('license_id', $licenses->pluck('id'))->online()->count();

        // Expiring soon (within 30 days)
        $expiringSoonCount = $licenses->where('status', 'active')
            ->where('expires_at', '>', now())
            ->where('expires_at', '<=', now()->addDays(30))
            ->count();

        // Recent activity (get from admin audit logs for user's licenses)
        $recentActivity = \App\Models\AdminAuditLog::whereIn('entity_id', $licenses->pluck('id'))
            ->where('entity_type', 'license')
            ->latest()
            ->take(5)
            ->get();

        // License summary (first active license or most recent)
        $summaryLicense = $licenses->where('status', 'active')->first() ?? $licenses->first();

        // Expiring licenses (for warnings)
        $expiringLicenses = $licenses->where('status', 'active')
            ->where('expires_at', '>', now())
            ->where('expires_at', '<=', now()->addDays(30))
            ->sortBy('expires_at')
            ->take(3);

        return view('client-dashboard', [
            'activeLicensesCount' => $activeLicensesCount,
            'totalDevicesCount' => $totalDevicesCount,
            'onlineDevicesCount' => $onlineDevicesCount,
            'expiringSoonCount' => $expiringSoonCount,
            'recentActivity' => $recentActivity,
            'summaryLicense' => $summaryLicense,
            'expiringLicenses' => $expiringLicenses,
        ]);
    }

    /**
     * Display the client security page.
     */
    public function clientSecurity(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Get user's licenses
        $licenseIds = $user->licenses()->pluck('id');

        // Get recent device activations
        $recentActivations = \App\Models\ActivationLog::whereIn('license_id', $licenseIds)
            ->where('success', true)
            ->latest()
            ->take(10)
            ->get();

        // Get recent security events related to user's licenses
        $securityEvents = \App\Models\SecurityAuditLog::whereHas('license', function ($query) use ($licenseIds) {
            $query->whereIn('id', $licenseIds);
        })->latest()
        ->take(10)
        ->get();

        // Get failed login attempts for this user's email
        $failedLogins = \App\Models\SecurityAuditLog::where('event_type', 'failed_login')
            ->whereJsonContains('details->email', $user->email)
            ->latest()
            ->take(5)
            ->get();

        return view('client-security', compact(
            'recentActivations',
            'securityEvents',
            'failedLogins'
        ));
    }

    /**
     * Display the client activity history page.
     */
    public function clientActivity(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Get user's licenses
        $licenseIds = $user->licenses()->pluck('id');
        $deviceIds = \App\Models\Device::whereIn('license_id', $licenseIds)->pluck('id');

        // Get all activity logs related to user's licenses and devices
        $allActivities = \App\Models\AdminAuditLog::where(function ($query) use ($licenseIds, $deviceIds) {
            $query->where(function ($q) use ($licenseIds) {
                $q->whereIn('entity_id', $licenseIds)
                  ->where('entity_type', 'license');
            })->orWhere(function ($q) use ($deviceIds) {
                $q->whereIn('entity_id', $deviceIds)
                  ->where('entity_type', 'device');
            });
        })->latest()
         ->paginate(20);

        return view('client-activity', compact('allActivities'));
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
