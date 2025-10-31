<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

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
            'page_content_cancellation',
            'page_content_terms',
            'terms_effective_date',
            'terms_conditions',
            'privacy_policy'
        ];

        // Fetch the settings from the database and format them for the view
        $settings = Setting::whereIn('key', $keys)
                           ->pluck('value', 'key');

        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'page_content_sevas' => 'nullable|string',
            'page_content_dress_code' => 'nullable|string',
            'page_content_privacy' => 'nullable|string',
            'page_content_cancellation' => 'nullable|string',
            'page_content_terms' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
            'privacy_policy' => 'nullable|string',
            'terms_effective_date' => 'nullable|date',
        ]);

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        Cache::forget('app_settings');

        return redirect()->back()->with('success', 'Website content updated successfully!');
    }
}
