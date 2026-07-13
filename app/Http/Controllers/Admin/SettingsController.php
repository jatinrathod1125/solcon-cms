<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\BagSize;
use App\Services\ActivityLogService;

class SettingsController extends Controller
{
    /**
     * Show the settings editing form.
     */
    public function edit()
    {
        $settings = [
            'factory_name' => Setting::get('factory_name', 'Solcon Industries'),
            'factory_logo' => Setting::get('factory_logo'),
            'report_header' => Setting::get('report_header', 'Solcon Industries Daily Production Report'),
            'footer_text' => Setting::get('footer_text', 'Solcon Production Management System'),
            'default_bag_size' => Setting::get('default_bag_size', '20'),
            'timezone' => Setting::get('timezone', 'Asia/Kolkata'),
        ];

        $bagSizes = BagSize::orderBy('value')->get();
        
        $timezones = [
            'UTC' => 'UTC (GMT)',
            'Asia/Kolkata' => 'Asia/Kolkata (IST)',
            'Asia/Dubai' => 'Asia/Dubai (GST)',
            'Europe/London' => 'Europe/London (BST/GMT)',
            'America/New_York' => 'America/New_York (EST/EDT)',
            'Asia/Singapore' => 'Asia/Singapore (SGT)',
        ];

        return view('admin.settings.edit', compact('settings', 'bagSizes', 'timezones'));
    }

    /**
     * Update settings in the database.
     */
    public function update(Request $request)
    {
        $request->validate([
            'factory_name' => 'required|string|max:100',
            'report_header' => 'required|string|max:200',
            'footer_text' => 'required|string|max:200',
            'default_bag_size' => 'required|numeric',
            'timezone' => 'required|string|max:50',
            'factory_logo' => 'nullable|image|max:2048',
        ]);

        Setting::set('factory_name', $request->input('factory_name'));
        Setting::set('report_header', $request->input('report_header'));
        Setting::set('footer_text', $request->input('footer_text'));
        Setting::set('default_bag_size', $request->input('default_bag_size'));
        Setting::set('timezone', $request->input('timezone'));

        // Handle Factory Logo Upload
        if ($request->hasFile('factory_logo')) {
            $logo = $request->file('factory_logo');
            $logoName = 'factory_logo_' . time() . '.' . $logo->getClientOriginalExtension();
            
            // Create images directory in public if it doesn't exist
            if (!file_exists(public_path('images'))) {
                mkdir(public_path('images'), 0755, true);
            }
            
            $logo->move(public_path('images'), $logoName);
            Setting::set('factory_logo', 'images/' . $logoName);
        }

        // Log Activity
        ActivityLogService::log(
            'SETTINGS_UPDATED',
            'Factory configuration parameters updated.',
            auth()->id()
        );

        return redirect()->route('admin.settings.edit')->with('success', 'Factory settings updated successfully.');
    }
}
