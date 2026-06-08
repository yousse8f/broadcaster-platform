<?php

namespace App\Http\Controllers;

use App\Models\License;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{
    /**
     * Display the user's account page.
     */
    public function index(): \Illuminate\View\View
    {
        $user = Auth::user();
        
        // Get user's licenses
        $licenses = $user->licenses;
        
        // Calculate subscription info
        $activeLicenses = $licenses->where('status', 'active')->count();
        $totalAllowedDevices = $licenses->sum('allowed_devices');
        $totalUsedDevices = $licenses->reduce(function ($carry, $license) {
            return $carry + $license->active_devices_count;
        }, 0);

        // Calculate device usage percentage
        $deviceUsagePercentage = $totalAllowedDevices > 0
            ? min(($totalUsedDevices / $totalAllowedDevices) * 100, 100)
            : 0;
        
        // Get next expiration date
        $nextExpiration = $licenses
            ->where('expires_at', '>', now())
            ->sortBy('expires_at')
            ->first();
        
        // Determine plan based on licenses
        $plan = $this->determinePlan($licenses);
        
        // Get last login from sessions
        $lastLogin = $this->getLastLogin();
        
        return view('account.index', compact(
            'user',
            'licenses',
            'activeLicenses',
            'totalAllowedDevices',
            'totalUsedDevices',
            'deviceUsagePercentage',
            'nextExpiration',
            'plan',
            'lastLogin'
        ));
    }

    /**
     * Update the user's profile information.
     */
    public function updateProfile(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . Auth::id()],
        ]);

        /** @var User $user */
        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        /** @var User $user */
        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password updated successfully.');
    }

    /**
     * Determine the user's plan based on their licenses.
     * @param Collection|License[] $licenses
     */
    private function determinePlan(Collection $licenses): string
    {
        if ($licenses->isEmpty()) {
            return 'Free';
        }

        $totalAllowedDevices = $licenses->sum('allowed_devices');

        if ($totalAllowedDevices >= 10) {
            return 'Enterprise';
        } elseif ($totalAllowedDevices >= 5) {
            return 'Professional';
        } elseif ($totalAllowedDevices >= 2) {
            return 'Standard';
        } else {
            return 'Basic';
        }
    }

    /**
     * Get the user's last login information.
     */
    private function getLastLogin(): string
    {
        $lastSession = DB::table('sessions')
            ->where('user_id', Auth::id())
            ->orderBy('last_activity', 'desc')
            ->first();

        if ($lastSession) {
            return date('Y-m-d h:i A', $lastSession->last_activity);
        }

        return 'Never';
    }
}
