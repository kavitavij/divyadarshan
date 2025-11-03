<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\PaymentGatewaySetting; // <-- IMPORT THE NEW MODEL

class SettingsController extends Controller
{
    // --- YOUR EXISTING METHODS FOR PAGE CONTENT ---

    /**
     * Show the form to edit the settings
     */
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

    /**
     * Update the page content settings
     */
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

    // --- NEW METHODS FOR PAYMENT GATEWAYS ---

    /**
     * Show the form for editing payment gateway settings.
     */
    public function paymentSettings()
    {
        $razorpay = PaymentGatewaySetting::firstOrCreate(['name' => 'razorpay']);
        $stripe = PaymentGatewaySetting::firstOrCreate(['name' => 'stripe']);

        return view('admin.settings.payment', compact('razorpay', 'stripe'));
    }

    /**
     * Update the payment gateway settings.
     */
    public function updatePaymentSettings(Request $request)
    {
        // --- Update Razorpay ---
        $razorpay = PaymentGatewaySetting::firstOrCreate(['name' => 'razorpay']);
        $razorpayData = $request->input('razorpay');

        $razorpay->is_active = isset($razorpayData['is_active']) ? 1 : 0;
        $razorpay->key = $razorpayData['key'];
        // Only update secret if a new one is provided and it's not empty
        if (!empty($razorpayData['secret'])) {
            $razorpay->secret = $razorpayData['secret'];
        }
        $razorpay->save();

        // --- Update Stripe ---
        $stripe = PaymentGatewaySetting::firstOrCreate(['name' => 'stripe']);
        $stripeData = $request->input('stripe');

        $stripe->is_active = isset($stripeData['is_active']) ? 1 : 0;
        $stripe->key = $stripeData['key'];
        // Only update secret if a new one is provided and it's not empty
        if (!empty($stripeData['secret'])) {
            $stripe->secret = $stripeData['secret'];
        }
        $stripe->save();

        return redirect()->back()->with('success', 'Payment settings updated successfully!');
    }
}

