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
}
