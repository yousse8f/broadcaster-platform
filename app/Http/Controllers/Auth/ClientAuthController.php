<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SecurityAuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ClientAuthController extends Controller
{
    /**
     * Display the client login view.
     */
    public function create(): View
    {
        return view('auth.client-login');
    }

    /**
     * Handle an incoming client authentication request.
     */
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'Password is required.',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Check if user is client
            if ($user->role !== 'client') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Access denied. Client access only.',
                ])->onlyInput('email');
            }

            return redirect()->intended(route('client.dashboard'));
        }

        // Log failed login attempt
        SecurityAuditLog::logEvent([
            'event_type' => 'failed_login',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'details' => [
                'email' => $request->email,
                'login_type' => 'client',
            ],
            'severity' => 'medium',
        ]);

        return back()->withErrors([
            'email' => 'Invalid email or password. Please check your credentials and try again.',
        ])->onlyInput('email');
    }

    /**
     * Destroy an authenticated client session.
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/client/login');
    }
}
