<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutController extends Controller
{
    // About Page
    public function about()
    {
        return view('pages.about');  // ✅ correct path
    }

    // Privacy Policy Page
    public function privacy()
    {
        return view('info.privacy'); // keep this if file is at resources/views/info/privacy.blade.php
    }

    // Cancellation Policy Page
    public function cancellation()
    {
        return view('info.cancellation'); // keep this if file is at resources/views/info/cancellation.blade.php
    }
}
