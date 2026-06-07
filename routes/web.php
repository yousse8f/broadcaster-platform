<?php

use App\Http\Controllers\ActivationController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\LicenseController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// Public API for license validation (no authentication required)
// This is used by external applications (e.g., Encoder) to validate license keys
Route::post('/api/license/validate', [LicenseController::class, 'validate'])
    ->name('api.license.validate');

// Public API for device activation (no authentication required)
// This is used by external applications (e.g., Encoder) to activate devices
Route::post('/api/device/activate', [ActivationController::class, 'activate'])
    ->name('api.device.activate');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->name('login.store');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
    
    Route::get('/activation-logs', [DashboardController::class, 'activationLogs'])
        ->name('activation-logs');
    
    // License Management (Admin only)
    Route::middleware('admin')->prefix('licenses')->name('licenses.')->group(function () {
        Route::get('/', [LicenseController::class, 'index'])->name('index');
        Route::get('/create', [LicenseController::class, 'create'])->name('create');
        Route::post('/', [LicenseController::class, 'store'])->name('store');
        Route::get('/{license}', [LicenseController::class, 'show'])->name('show');
        Route::get('/{license}/edit', [LicenseController::class, 'edit'])->name('edit');
        Route::put('/{license}', [LicenseController::class, 'update'])->name('update');
        Route::delete('/{license}', [LicenseController::class, 'destroy'])->name('destroy');
        Route::post('/{license}/activate', [LicenseController::class, 'activate'])->name('activate');
        Route::post('/{license}/suspend', [LicenseController::class, 'suspend'])->name('suspend');
    });

    // Device Management (Admin only)
    Route::middleware('admin')->prefix('devices')->name('devices.')->group(function () {
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

    // Settings Management (Admin only)
    Route::middleware('admin')->prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::post('/', [SettingsController::class, 'update'])->name('update');
        Route::post('/delete-logo', [SettingsController::class, 'deleteLogo'])->name('deleteLogo');
        Route::post('/delete-favicon', [SettingsController::class, 'deleteFavicon'])->name('deleteFavicon');
    });
});
