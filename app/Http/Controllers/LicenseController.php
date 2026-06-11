<?php

namespace App\Http\Controllers;

use App\Models\AdminAuditLog;
use App\Models\License;
use App\Models\LicenseValidationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Carbon\Carbon;

class LicenseController extends Controller
{
    /**
     * Display a listing of licenses.
     */
    public function index(): View
    {
        $licenses = License::with('user')->latest()->paginate(10);
        return view('licenses.index', compact('licenses'));
    }

    /**
     * Display the authenticated user's licenses.
     */
    public function myLicenses(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $licenses = $user->licenses()->with('devices')->latest()->paginate(10);
        return view('licenses.my-licenses', compact('licenses'));
    }

    /**
     * Display the specified license for client view.
     */
    public function clientShow(License $license): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Security: Ensure user can only view their own licenses
        if ($license->user_id !== $user->id) {
            abort(403, 'You do not have permission to view this license.');
        }

        $license->load(['user', 'devices']);

        // Get activity logs for this license
        $activityLogs = \App\Models\AdminAuditLog::where('entity_type', 'license')
            ->where('entity_id', $license->id)
            ->latest()
            ->take(10)
            ->get();

        return view('licenses.client-show', compact('license', 'activityLogs'));
    }

    /**
     * Show the form for creating a new license.
     */
    public function create(): View
    {
        $users = \App\Models\User::where('role', 'client')->get();
        return view('licenses.create', compact('users'));
    }

    /**
     * Store a newly created license in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'license_key' => ['nullable', 'string', 'unique:licenses,license_key', 'max:100'],
            'user_id' => ['required', 'exists:users,id'],
            'status' => ['required', 'in:active,expired,suspended'],
            'expires_at' => ['required', 'date', 'after:today'],
            'allowed_devices' => ['required', 'integer', 'min:1', 'max:10'],
        ], [
            'license_key.unique' => 'This license key already exists.',
            'user_id.required' => 'Please select a user.',
            'user_id.exists' => 'Selected user does not exist.',
            'status.required' => 'Please select a status.',
            'status.in' => 'Invalid status selected.',
            'expires_at.required' => 'Please select an expiration date.',
            'expires_at.date' => 'Invalid date format.',
            'expires_at.after' => 'Expiration date must be in the future.',
            'allowed_devices.required' => 'Please specify the number of allowed devices.',
            'allowed_devices.integer' => 'Allowed devices must be a number.',
            'allowed_devices.min' => 'At least 1 device is required.',
            'allowed_devices.max' => 'Maximum 10 devices are allowed.',
        ]);

        // Auto-generate license key if not provided
        if (empty($validated['license_key'])) {
            $validated['license_key'] = strtoupper(Str::random(3) . '-' . Str::random(3) . '-' . Str::random(3));
        }

        $license = License::create($validated);

        // Log admin action
        AdminAuditLog::logAction([
            'admin_id' => Auth::id(),
            'action' => 'license_created',
            'entity_type' => 'license',
            'entity_id' => $license->id,
            'new_values' => $validated,
            'description' => "Created license {$license->license_key} for user {$validated['user_id']}",
        ]);

        return redirect()->route('admin.licenses.index')
            ->with('success', 'License created successfully.');
    }

    /**
     * Display the specified license.
     */
    public function show(License $license): View
    {
        $license->load(['user', 'devices']);
        return view('licenses.show', compact('license'));
    }

    /**
     * Show the form for editing the specified license.
     */
    public function edit(License $license): View
    {
        $users = \App\Models\User::where('role', 'client')->get();
        return view('licenses.edit', compact('license', 'users'));
    }

    /**
     * Update the specified license in storage.
     */
    public function update(Request $request, License $license)
    {
        $validated = $request->validate([
            'license_key' => ['nullable', 'string', 'unique:licenses,license_key,' . $license->id, 'max:100'],
            'user_id' => ['required', 'exists:users,id'],
            'status' => ['required', 'in:active,expired,suspended'],
            'expires_at' => ['required', 'date', 'after:today'],
            'allowed_devices' => ['required', 'integer', 'min:1', 'max:10'],
        ], [
            'license_key.unique' => 'This license key already exists.',
            'user_id.required' => 'Please select a user.',
            'user_id.exists' => 'Selected user does not exist.',
            'status.required' => 'Please select a status.',
            'status.in' => 'Invalid status selected.',
            'expires_at.required' => 'Please select an expiration date.',
            'expires_at.date' => 'Invalid date format.',
            'expires_at.after' => 'Expiration date must be in the future.',
            'allowed_devices.required' => 'Please specify the number of allowed devices.',
            'allowed_devices.integer' => 'Allowed devices must be a number.',
            'allowed_devices.min' => 'At least 1 device is required.',
            'allowed_devices.max' => 'Maximum 10 devices are allowed.',
        ]);

        // Auto-generate license key if not provided
        if (empty($validated['license_key'])) {
            $validated['license_key'] = strtoupper(Str::random(3) . '-' . Str::random(3) . '-' . Str::random(3));
        }

        $oldValues = $license->getOriginal();
        $license->update($validated);

        // Log admin action
        AdminAuditLog::logAction([
            'admin_id' => Auth::id(),
            'action' => 'license_updated',
            'entity_type' => 'license',
            'entity_id' => $license->id,
            'old_values' => $oldValues,
            'new_values' => $validated,
            'description' => "Updated license {$license->license_key}",
        ]);

        return redirect()->route('admin.licenses.index')
            ->with('success', 'License updated successfully.');
    }

    /**
     * Remove the specified license from storage (soft delete by suspending).
     */
    public function destroy(License $license)
    {
        // Soft delete by suspending instead of actual deletion
        $license->update(['status' => 'suspended']);

        return redirect()->route('admin.licenses.index')
            ->with('success', 'License suspended successfully.');
    }

    /**
     * Activate the specified license.
     */
    public function activate(License $license)
    {
        $oldStatus = $license->status;
        $license->update(['status' => 'active']);

        // Log admin action
        AdminAuditLog::logAction([
            'admin_id' => Auth::id(),
            'action' => 'license_updated',
            'entity_type' => 'license',
            'entity_id' => $license->id,
            'old_values' => ['status' => $oldStatus],
            'new_values' => ['status' => 'active'],
            'description' => "Activated license {$license->license_key}",
        ]);

        return redirect()->route('admin.licenses.index')
            ->with('success', 'License activated successfully.');
    }

    /**
     * Suspend the specified license.
     */
    public function suspend(License $license)
    {
        $oldStatus = $license->status;
        $license->update(['status' => 'suspended']);

        // Log admin action
        AdminAuditLog::logAction([
            'admin_id' => Auth::id(),
            'action' => 'license_updated',
            'entity_type' => 'license',
            'entity_id' => $license->id,
            'old_values' => ['status' => $oldStatus],
            'new_values' => ['status' => 'suspended'],
            'description' => "Suspended license {$license->license_key}",
        ]);

        return redirect()->route('admin.licenses.index')
            ->with('success', 'License suspended successfully.');
    }

    /**
     * Renew the specified license.
     */
    public function renew(Request $request, License $license)
    {
        $validated = $request->validate([
            'days' => ['required', 'integer', 'in:30,90,180,365'],
        ]);

        $days = $validated['days'];
        $oldExpiration = $license->expires_at;
        $oldStatus = $license->status;
        $currentExpiration = $license->expires_at ?? now();
        $newExpiration = $currentExpiration->addDays($days);

        $license->update([
            'expires_at' => $newExpiration,
            'status' => 'active', // Reactivate if expired
        ]);

        // Log admin action
        AdminAuditLog::logAction([
            'admin_id' => Auth::id(),
            'action' => 'license_renewed',
            'entity_type' => 'license',
            'entity_id' => $license->id,
            'old_values' => [
                'expires_at' => $oldExpiration ? $oldExpiration->toISOString() : null,
                'status' => $oldStatus,
            ],
            'new_values' => [
                'expires_at' => $newExpiration->toISOString(),
                'status' => 'active',
            ],
            'description' => "Renewed license {$license->license_key} for {$days} days",
        ]);

        return redirect()->route('admin.licenses.show', $license)
            ->with('success', "License renewed for {$days} days successfully.");
    }

    /**
     * Get license information (public API for external applications).
     * Returns detailed license information including status, expiration, and device counts.
     */
    public function info(Request $request)
    {
        $validated = $request->validate([
            'license_key' => ['required', 'string', 'max:100'],
        ]);

        $license = License::where('license_key', $validated['license_key'])->first();

        if (!$license) {
            return response()->json([
                'success' => false,
                'message' => 'License not found',
            ], 404);
        }

        // Check if license is suspended
        if ($license->status === 'suspended') {
            return response()->json([
                'success' => false,
                'message' => 'License suspended',
            ], 403);
        }

        // Check if license is expired
        if ($license->expires_at && $license->expires_at->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'License expired',
            ], 403);
        }

        // Load license with user and devices
        $license->load(['user', 'devices']);

        return response()->json([
            'success' => true,
            'data' => [
                'license_key' => $license->license_key,
                'status' => $license->status,
                'expiration' => $license->expires_at ? $license->expires_at->toISOString() : null,
                'allowed_devices' => $license->allowed_devices,
                'used_devices' => $license->devices()->where('status', 'active')->count(),
                'customer' => [
                    'id' => $license->user->id,
                    'name' => $license->user->name,
                    'email' => $license->user->email,
                ],
                'created_at' => $license->created_at->toISOString(),
            ],
        ]);
    }

    /**
     * Validate a license key (public API for external applications).
     * This endpoint does NOT register devices or activate licenses.
     * It only checks if a license is valid for use.
     */
    public function validate(Request $request)
    {
        $validated = $request->validate([
            'license_key' => ['required', 'string', 'max:100'],
        ]);

        $licenseKey = $validated['license_key'];
        $ipAddress = $request->ip();
        $checkedAt = Carbon::now();

        // Phase 1: Search for the license
        $license = License::where('license_key', $licenseKey)->first();

        if (!$license) {
            // Log the failure
            LicenseValidationLog::create([
                'license_key' => $licenseKey,
                'ip_address' => $ipAddress,
                'result' => 'failure',
                'message' => 'License not found',
                'license_id' => null,
            ]);

            return response()->json([
                'valid' => false,
                'message' => 'License not found',
                'checked_at' => $checkedAt->toISOString(),
            ], 404);
        }

        // Phase 2: Check license status
        if (in_array($license->status, ['suspended', 'revoked', 'inactive'])) {
            // Log the failure
            LicenseValidationLog::create([
                'license_key' => $licenseKey,
                'ip_address' => $ipAddress,
                'result' => 'failure',
                'message' => 'License ' . $license->status,
                'license_id' => $license->id,
            ]);

            return response()->json([
                'valid' => false,
                'message' => 'License ' . $license->status,
                'checked_at' => $checkedAt->toISOString(),
            ], 403);
        }

        // Phase 3: Check expiration date
        if ($license->expires_at && $license->expires_at->isPast()) {
            // Log the failure
            LicenseValidationLog::create([
                'license_key' => $licenseKey,
                'ip_address' => $ipAddress,
                'result' => 'failure',
                'message' => 'License expired',
                'license_id' => $license->id,
            ]);

            return response()->json([
                'valid' => false,
                'message' => 'License expired',
                'checked_at' => $checkedAt->toISOString(),
            ], 403);
        }

        // Phase 4: Check if the associated user exists and is active
        $user = $license->user;
        if (!$user) {
            // Log the failure
            LicenseValidationLog::create([
                'license_key' => $licenseKey,
                'ip_address' => $ipAddress,
                'result' => 'failure',
                'message' => 'Associated user not found',
                'license_id' => $license->id,
            ]);

            return response()->json([
                'valid' => false,
                'message' => 'Associated user not found',
                'checked_at' => $checkedAt->toISOString(),
            ], 403);
        }

        // Phase 5: Return success result
        // Log the success
        LicenseValidationLog::create([
            'license_key' => $licenseKey,
            'ip_address' => $ipAddress,
            'result' => 'success',
            'message' => 'License validated successfully',
            'license_id' => $license->id,
        ]);

        return response()->json([
            'valid' => true,
            'license_key' => $license->license_key,
            'status' => $license->status,
            'expires_at' => $license->expires_at ? $license->expires_at->toISOString() : null,
            'allowed_devices' => $license->allowed_devices,
            'checked_at' => $checkedAt->toISOString(),
        ], 200);
    }
}
