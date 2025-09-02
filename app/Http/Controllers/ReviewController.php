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
            'email' => 'required|email|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'message' => 'required|string',
            'review_type' => 'required|string|in:general,darshan,seva,accommodation',
        ]);

        Review::create($request->all());

        return redirect()->route('reviews.index')->with('success', 'Thanks for your review!');
    }

    /**
     * Handle the "like" functionality for a review.
     *
     * @param \App\Models\Review $review
     * @return \Illuminate\Http\JsonResponse
     */
    public function like(Review $review)
    {
        $review->increment('likes');
        $review->refresh(); // Reload the model from the database

        return response()->json(['likes' => $review->likes]);
    }
}

