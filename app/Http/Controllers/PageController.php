<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;

class PageController extends Controller
{
    public function about()
    {
        $reviews = Review::latest()->get();
        return view('pages.about', compact('reviews'));
    }
}
