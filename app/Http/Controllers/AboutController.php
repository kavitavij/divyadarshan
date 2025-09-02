<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Temple;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    /**
     * Display the about page with the latest reviews.
     */
    public function about()
    {
        // Fetch all temples for the navigation dropdown
        $allTemples = Temple::all();

        // Fetch the 3 most recent reviews to display as testimonials
        $reviews = Review::latest()->take(3)->get();

        // Return the view and pass the data to it
        return view('pages.about', compact('allTemples', 'reviews'));
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
