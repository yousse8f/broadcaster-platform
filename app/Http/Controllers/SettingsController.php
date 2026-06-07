<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        $settings = $this->getSettings();
        return view('settings.index', compact('settings'));
    }

    /**
     * Update the settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png|max:1024',
        ]);

        // Update text settings
        $settings = [
            'site_name' => $request->site_name,
            'site_description' => $request->site_description,
        ];

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = 'logo.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('images/logo'), $logoName);
            $settings['logo'] = 'images/logo/' . $logoName;
        }

        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            $favicon = $request->file('favicon');
            $faviconName = 'favicon.' . $favicon->getClientOriginalExtension();
            $favicon->move(public_path('images/logo'), $faviconName);
            $settings['favicon'] = 'images/logo/' . $faviconName;
        }

        // Save settings to file
        $this->saveSettings($settings);

        return redirect()->back()->with('success', 'Settings updated successfully!');
    }

    /**
     * Get current settings.
     */
    private function getSettings()
    {
        $settingsFile = storage_path('app/settings.json');
        
        if (!file_exists($settingsFile)) {
            return [
                'site_name' => 'broadcast',
                'site_description' => 'License and Device Management System',
                'logo' => 'images/logo/logo maester.webp',
                'favicon' => 'favicon.ico',
            ];
        }

        return json_decode(file_get_contents($settingsFile), true);
    }

    /**
     * Save settings to file.
     */
    private function saveSettings(array $settings)
    {
        $currentSettings = $this->getSettings();
        $mergedSettings = array_merge($currentSettings, $settings);
        
        $settingsFile = storage_path('app/settings.json');
        file_put_contents($settingsFile, json_encode($mergedSettings, JSON_PRETTY_PRINT));
    }

    /**
     * Delete logo.
     */
    public function deleteLogo()
    {
        $settings = $this->getSettings();
        
        if (isset($settings['logo']) && file_exists(public_path($settings['logo']))) {
            unlink(public_path($settings['logo']));
        }

        $settings['logo'] = 'images/logo/logo maester.webp';
        $this->saveSettings($settings);

        return redirect()->back()->with('success', 'Logo deleted successfully!');
    }

    /**
     * Delete favicon.
     */
    public function deleteFavicon()
    {
        $settings = $this->getSettings();
        
        if (isset($settings['favicon']) && file_exists(public_path($settings['favicon']))) {
            unlink(public_path($settings['favicon']));
        }

        $settings['favicon'] = 'favicon.ico';
        $this->saveSettings($settings);

        return redirect()->back()->with('success', 'Favicon deleted successfully!');
    }
}
