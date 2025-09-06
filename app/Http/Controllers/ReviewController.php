<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Models\StayBooking;
use Illuminate\Support\Facades\Auth;
class ReviewController extends Controller
{
    public function index()
    {
        $reviews = \App\Models\Review::latest()->get();
        return view('reviews.index', compact('reviews'));
    }
    public function create(StayBooking $stayBooking)
    {
        // Authorization checks
        abort_if($stayBooking->user_id !== Auth::id(), 403, 'This is not your booking.');
        abort_if($stayBooking->review()->exists(), 403, 'You have already submitted a review for this stay.');
        abort_if(now()->lt($stayBooking->check_out_date), 403, 'You can only review a stay after the check-out date.');

        return view('reviews.create', compact('stayBooking'));
    }
    public function store(Request $request, StayBooking $stayBooking)
    {
        // Authorization checks (repeat for security)
        abort_if($stayBooking->user_id !== Auth::id(), 403);
        abort_if($stayBooking->review()->exists(), 403);
        abort_if(now()->lt($stayBooking->check_out_date), 403);

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:5000',
        ]);

        Review::create([
            'user_id' => Auth::id(),
            'hotel_id' => $stayBooking->room->hotel->id,
            'stay_booking_id' => $stayBooking->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'review_type' => 'accommodation', // Set the type automatically
        ]);

        return redirect()->route('profile.my-stays.index')->with('success', 'Thank you for your review!');
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
        return response()->json(['likes' => $review->likes]);
    }
}

