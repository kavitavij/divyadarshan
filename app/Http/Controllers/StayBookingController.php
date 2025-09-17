<?php

namespace App\Http\Controllers;

use App\Models\StayBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StayBookingController extends Controller
{
    public function payment(StayBooking $stayBooking)
    {
        if ($stayBooking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $stayBooking->load('room.hotel');

        return view('stays.payment', compact('stayBooking'));
    }

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

}
