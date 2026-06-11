<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\ActivationController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ClientAuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\HeartbeatController;
use App\Http\Controllers\LicenseController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('client.dashboard');
        }
    }
    return redirect()->route('admin.login');
})->name('home');

// Public API for license validation (no authentication required)
// This is used by external applications (e.g., Encoder) to validate license keys
// Rate limit: 60 requests per minute
Route::post('/api/license/validate', [LicenseController::class, 'validate'])
    ->middleware('throttle:60,1')
    ->name('api.license.validate');

// Public API for license information (no authentication required)
// This is used by external applications (e.g., Encoder) to get license details
// Rate limit: 60 requests per minute
Route::get('/api/license/info', [LicenseController::class, 'info'])
    ->middleware('throttle:60,1')
    ->name('api.license.info');

// Public API for device activation (no authentication required)
// This is used by external applications (e.g., Encoder) to activate devices
// Rate limit: 10 requests per minute (strict limit for security)
Route::post('/api/device/activate', [ActivationController::class, 'activate'])
    ->middleware('throttle:10,1')
    ->name('api.device.activate');

// Public API for device heartbeat (no authentication required)
// This is used by external applications (e.g., Encoder) to send heartbeat signals
// Rate limit: 120 requests per minute (higher limit for frequent updates)
Route::post('/api/device/heartbeat', [HeartbeatController::class, 'heartbeat'])
    ->middleware('throttle:120,1')
    ->name('api.device.heartbeat');

// Public API for device information (no authentication required)
// This is used by external applications (e.g., Encoder) to get device details
// Rate limit: 60 requests per minute
Route::get('/api/device/info', [DeviceController::class, 'info'])
    ->middleware('throttle:60,1')
    ->name('api.device.info');

// Public API for device deactivation (no authentication required)
// This is used by external applications (e.g., Encoder) to deactivate devices
// Rate limit: 10 requests per minute (strict limit for security)
Route::post('/api/device/deactivate', [DeviceController::class, 'deactivate'])
    ->middleware('throttle:10,1')
    ->name('api.device.deactivate');

// Admin Login Routes
Route::prefix('admin')->middleware('guest')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'create'])
        ->name('admin.login');

    Route::post('/login', [AdminAuthController::class, 'store'])
        ->name('admin.login.store');
});

// Client Login Routes
Route::prefix('client')->middleware('guest')->group(function () {
    Route::get('/login', [ClientAuthController::class, 'create'])
        ->name('client.login');

    Route::post('/login', [ClientAuthController::class, 'store'])
        ->name('client.login.store');
});

// Logout Routes (separate for admin and client)
Route::post('/admin/logout', [AdminAuthController::class, 'destroy'])
    ->middleware('auth')
    ->name('admin.logout');

Route::post('/client/logout', [ClientAuthController::class, 'destroy'])
    ->middleware('auth')
    ->name('client.logout');

// Protected routes
Route::middleware('auth')->group(function () {
    // Account management (available to both admin and client)
    Route::get('/account', [AccountController::class, 'index'])
        ->name('account.index');
    Route::post('/account/update-profile', [AccountController::class, 'updateProfile'])
        ->name('account.update-profile');
    Route::post('/account/update-password', [AccountController::class, 'updatePassword'])
        ->name('account.update-password');
});

// Admin Routes - Protected by admin middleware
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])
        ->name('dashboard');

    // Activation Logs
    Route::get('/activation-logs', [DashboardController::class, 'activationLogs'])
        ->name('activation-logs');

    // License Management
    Route::prefix('licenses')->name('licenses.')->group(function () {
        Route::get('/', [LicenseController::class, 'index'])->name('index');
        Route::get('/create', [LicenseController::class, 'create'])->name('create');
        Route::post('/', [LicenseController::class, 'store'])->name('store');
        Route::get('/{license}', [LicenseController::class, 'show'])->name('show');
        Route::get('/{license}/edit', [LicenseController::class, 'edit'])->name('edit');
        Route::put('/{license}', [LicenseController::class, 'update'])->name('update');
        Route::delete('/{license}', [LicenseController::class, 'destroy'])->name('destroy');
        Route::post('/{license}/activate', [LicenseController::class, 'activate'])->name('activate');
        Route::post('/{license}/suspend', [LicenseController::class, 'suspend'])->name('suspend');
        Route::post('/{license}/renew', [LicenseController::class, 'renew'])->name('renew');
    });

    // Device Management
    Route::prefix('devices')->name('devices.')->group(function () {
        Route::get('/', [DeviceController::class, 'index'])->name('index');
        Route::get('/create', [DeviceController::class, 'create'])->name('create');
        Route::post('/', [DeviceController::class, 'store'])->name('store');
        Route::get('/{device}', [DeviceController::class, 'show'])->name('show');
        Route::get('/{device}/edit', [DeviceController::class, 'edit'])->name('edit');
        Route::put('/{device}', [DeviceController::class, 'update'])->name('update');
        Route::delete('/{device}', [DeviceController::class, 'destroy'])->name('destroy');
        Route::post('/{device}/activate', [DeviceController::class, 'activate'])->name('activate');
        Route::post('/{device}/suspend', [DeviceController::class, 'suspend'])->name('suspend');
        Route::post('/{device}/revoke', [DeviceController::class, 'revoke'])->name('revoke');
    });

    // System Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::post('/general', [SettingsController::class, 'updateGeneral'])->name('update-general');
        Route::post('/license', [SettingsController::class, 'updateLicense'])->name('update-license');
        Route::post('/security', [SettingsController::class, 'updateSecurity'])->name('update-security');
        Route::post('/delete-logo', [SettingsController::class, 'deleteLogo'])->name('delete-logo');
        Route::post('/generate-api-secret', [SettingsController::class, 'generateApiSecret'])->name('generate-api-secret');
    });
});

// Client Routes - Protected by client middleware
Route::middleware(['auth', 'client'])->prefix('client')->name('client.')->group(function () {
    // Client Dashboard
    Route::get('/dashboard', [DashboardController::class, 'clientDashboard'])
        ->name('dashboard');

    // My Licenses
    Route::get('/licenses', [LicenseController::class, 'myLicenses'])
        ->name('licenses');
    Route::get('/licenses/{license}', [LicenseController::class, 'clientShow'])
        ->name('licenses.show');

    // My Devices
    Route::get('/devices', [DeviceController::class, 'myDevices'])
        ->name('devices');
    Route::get('/devices/{device}', [DeviceController::class, 'clientShow'])
        ->name('devices.show');
    Route::post('/devices/{device}/revoke', [DeviceController::class, 'clientRevoke'])
        ->name('devices.revoke');

    // Security & Activity
    Route::get('/security', [DashboardController::class, 'clientSecurity'])
        ->name('security');
    Route::get('/activity', [DashboardController::class, 'clientActivity'])
        ->name('activity');
});
