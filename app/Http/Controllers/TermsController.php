<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TermsController extends Controller
{
    public function index()
    {
        // Fetch Terms content from settings (if present) so the view can render admin-edited Terms
        $settings = \App\Models\Setting::whereIn('key', ['page_content_terms', 'terms_effective_date'])
                    ->pluck('value', 'key');

        return view('pages.terms', compact('settings'));
    }
}
