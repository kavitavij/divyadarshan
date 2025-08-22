<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = \App\Models\Review::latest()->get();
        return view('reviews.index', compact('reviews'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'message' => 'required|string',
        ]);

        Review::create($request->all());

        return redirect()->route('reviews.index')->with('success', 'Thanks for your review!');
    }
}
