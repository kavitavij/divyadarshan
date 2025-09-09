<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    // Show the form to edit the settings
    public function edit()
    {
        // Define the keys for the content we want to manage
        $keys = [
            'page_content_sevas',
            'page_content_dress_code',
            'page_content_privacy',
            'page_content_cancellation'
        ];

        // Fetch the settings from the database and format them for the view
        $settings = Setting::whereIn('key', $keys)
                           ->pluck('value', 'key');

        return view('admin.settings.edit', compact('settings'));
    }

    // Update the settings in the database
    public function update(Request $request)
    {
        $data = $request->validate([
            'page_content_sevas' => 'nullable|string',
            'page_content_dress_code' => 'nullable|string',
            'page_content_privacy' => 'nullable|string',
            'page_content_cancellation' => 'nullable|string',
        ]);

        // Loop through the data and update or create each setting
        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->back()->with('success', 'Website content updated successfully!');
    }
}
