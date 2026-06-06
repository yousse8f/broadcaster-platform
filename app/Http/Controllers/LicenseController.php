<?php

namespace App\Http\Controllers;

use App\Models\License;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LicenseController extends Controller
{
    /**
     * Display a listing of licenses.
     */
    public function index(): View
    {
        $licenses = License::with('user')->latest()->get();
        return view('licenses.index', compact('licenses'));
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

        License::create($validated);

        return redirect()->route('licenses.index')
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

        $license->update($validated);

        return redirect()->route('licenses.index')
            ->with('success', 'License updated successfully.');
    }

    /**
     * Remove the specified license from storage (soft delete by suspending).
     */
    public function destroy(License $license)
    {
        // Soft delete by suspending instead of actual deletion
        $license->update(['status' => 'suspended']);

        return redirect()->route('licenses.index')
            ->with('success', 'License suspended successfully.');
    }
}
