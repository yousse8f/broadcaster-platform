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
        $stats = [
            'total_licenses' => \App\Models\License::count(),
            'active_licenses' => \App\Models\License::where('status', 'active')->count(),
            'total_users' => \App\Models\User::count(),
            'total_devices' => \App\Models\Device::count(),
            'active_devices' => \App\Models\Device::where('status', 'active')->count(),
            'online_devices' => \App\Models\Device::online()->count(),
        ];

        return view('dashboard', compact('stats'));
    }
}
