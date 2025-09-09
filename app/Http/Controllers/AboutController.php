<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Temple;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Models\Faq;

class AboutController extends Controller
{
    /**
     * Display the about page with the latest reviews and dynamic content.
     */
    public function about(Request $request) // ✅ 2. ADD 'Request $request'
    {
        // Fetch all temples for the navigation dropdown
        $allTemples = Temple::all();

        // Fetch the 3 most recent reviews to display as testimonials
        $reviews = Review::latest()->take(3)->get();

        // Define the keys for the content you want to fetch
        $setting_keys = [
            'page_content_sevas',
            'page_content_dress_code',
            'page_content_privacy',
            'page_content_cancellation'
        ];
        $settings = Setting::whereIn('key', $setting_keys)->pluck('value', 'key');

        // Fetch only the published FAQs to display to users
        $faqs = Faq::where('is_published', true)->latest()->get();

        // ✅ 3. UPDATED LOGIC: This now checks for the URL parameter
        $openModal = session('open_faq_modal')
                  || session('open_contact_modal')
                  || session()->has('errors')
                  || $request->input('open') === 'contact'; // Checks for ?open=contact

        // This variable tells Alpine.js which content to load if the modal opens automatically
        $modalContentType = 'contact'; // Default to contact
        if (session('open_faq_modal') || (session()->has('errors') && old('question'))) {
            $modalContentType = 'faq';
        }

        // ✅ 4. PASS ALL VARIABLES: Pass the new variable to the view
        return view('pages.about', compact('allTemples', 'reviews', 'settings', 'faqs', 'openModal', 'modalContentType'));
    }

    /**
     * Display the privacy policy page.
     */
    public function privacy()
    {
        return view('info.privacy');
    }

    /**
     * Display the cancellation policy page.
     */
    public function cancellation()
    {
        return view('info.cancellation');
    }
}
