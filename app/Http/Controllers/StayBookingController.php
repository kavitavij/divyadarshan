<?php

namespace App\Http\Controllers;

use App\Models\StayBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StayBookingController extends Controller
{
    // --- Add these two methods to your existing controller ---

    /**
     * Display the payment page for a specific stay booking.
     */
    public function payment(StayBooking $stayBooking)
    {
        // Security check: Ensure the user owns this booking
        if ($stayBooking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Load the relationships to avoid extra queries in the view
        $stayBooking->load('room.hotel');

        // Return the dedicated payment view
        return view('stays.payment', compact('stayBooking'));
    }

    /**
     * Confirm the booking after a successful payment.
     */
    public function confirm(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:stay_bookings,id'
        ]);

        $stayBooking = StayBooking::findOrFail($request->booking_id);

        // Update the status to 'Confirmed'
        $stayBooking->status = 'Confirmed';
        $stayBooking->save();

        return redirect()->route('home')->with('success', 'Your accommodation is confirmed! Thank you for booking.');
    }

    // You likely already have 'index', 'create', and 'store' methods here.
    // Leave them as they are.
}
