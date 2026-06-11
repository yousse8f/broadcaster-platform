<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        $settings = Settings::getCurrent();
        $timezones = $this->getTimezones();
        return view('settings.index', compact('settings', 'timezones'));
    }

    /**
     * Update general settings.
     */
    public function updateGeneral(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'support_email' => 'nullable|email|max:255',
            'support_url' => 'nullable|url|max:500',
            'timezone' => 'required|string|max:100',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $settings = Settings::getCurrent();
        
        $settings->company_name = $request->company_name;
        $settings->support_email = $request->support_email;
        $settings->support_url = $request->support_url;
        $settings->timezone = $request->timezone;

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($settings->logo && file_exists(public_path($settings->logo))) {
                unlink(public_path($settings->logo));
            }

            $logo = $request->file('logo');
            $logoName = 'logo-' . time() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('images/logo'), $logoName);
            $settings->logo = 'images/logo/' . $logoName;
        }

        $settings->save();
        $settings->clearCache();

        return redirect()->back()->with('success', 'General settings updated successfully!');
    }

    /**
     * Update license settings.
     */
    public function updateLicense(Request $request)
    {
        $request->validate([
            'default_expiration' => 'required|integer|min:1|max:3650',
            'default_devices_limit' => 'required|integer|min:1|max:100',
            'heartbeat_timeout' => 'required|integer|min:30|max:3600',
            'validation_timeout' => 'required|integer|min:10|max:300',
        ]);

        $settings = Settings::getCurrent();
        
        $settings->default_expiration = $request->default_expiration;
        $settings->default_devices_limit = $request->default_devices_limit;
        $settings->heartbeat_timeout = $request->heartbeat_timeout;
        $settings->validation_timeout = $request->validation_timeout;

        $settings->save();
        $settings->clearCache();

        return redirect()->back()->with('success', 'License settings updated successfully!');
    }

    /**
     * Update security settings.
     */
    public function updateSecurity(Request $request)
    {
        $request->validate([
            'rate_limit' => 'required|integer|min:1|max:1000',
            'max_activations_per_day' => 'required|integer|min:1|max:100',
            'api_secret' => 'nullable|string|min:16|max:255',
            'allowed_origins' => 'nullable|string',
        ]);

        $settings = Settings::getCurrent();
        
        $settings->rate_limit = $request->rate_limit;
        $settings->max_activations_per_day = $request->max_activations_per_day;
        
        // Only update API secret if provided
        if ($request->filled('api_secret')) {
            $settings->api_secret = $request->api_secret;
        }

        // Parse allowed origins
        if ($request->filled('allowed_origins')) {
            $origins = array_filter(array_map('trim', explode(',', $request->allowed_origins)));
            $settings->allowed_origins = !empty($origins) ? $origins : null;
        } else {
            $settings->allowed_origins = null;
        }

        $settings->save();
        $settings->clearCache();

        return redirect()->back()->with('success', 'Security settings updated successfully!');
    }

    /**
     * Delete logo.
     */
    public function deleteLogo()
    {
        $settings = Settings::getCurrent();
        
        if ($settings->logo && file_exists(public_path($settings->logo))) {
            unlink(public_path($settings->logo));
        }

        $settings->logo = null;
        $settings->save();
        $settings->clearCache();

        return redirect()->back()->with('success', 'Logo deleted successfully!');
    }

    /**
     * Generate new API secret.
     */
    public function generateApiSecret()
    {
        $settings = Settings::getCurrent();
        $settings->api_secret = bin2hex(random_bytes(32));
        $settings->save();
        $settings->clearCache();

        return redirect()->back()->with('success', 'New API secret generated successfully!');
    }

    /**
     * Get list of available timezones.
     */
    private function getTimezones(): array
    {
        return [
            'UTC' => 'UTC',
            'Africa/Cairo' => 'Africa/Cairo',
            'Africa/Casablanca' => 'Africa/Casablanca',
            'Africa/Johannesburg' => 'Africa/Johannesburg',
            'Africa/Nairobi' => 'Africa/Nairobi',
            'America/New_York' => 'America/New_York',
            'America/Chicago' => 'America/Chicago',
            'America/Denver' => 'America/Denver',
            'America/Los_Angeles' => 'America/Los_Angeles',
            'America/Sao_Paulo' => 'America/Sao_Paulo',
            'Asia/Tokyo' => 'Asia/Tokyo',
            'Asia/Shanghai' => 'Asia/Shanghai',
            'Asia/Dubai' => 'Asia/Dubai',
            'Asia/Kolkata' => 'Asia/Kolkata',
            'Asia/Singapore' => 'Asia/Singapore',
            'Australia/Sydney' => 'Australia/Sydney',
            'Europe/London' => 'Europe/London',
            'Europe/Paris' => 'Europe/Paris',
            'Europe/Berlin' => 'Europe/Berlin',
            'Europe/Moscow' => 'Europe/Moscow',
            'Pacific/Auckland' => 'Pacific/Auckland',
        ];
    }
}
