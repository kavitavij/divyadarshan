<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class CheckInController extends Controller
{
    /**
     * Display the booking details for verification.
     */
    public function show($token)
    {
        // Find the booking by its unique token. Fail if not found.
        $booking = Booking::where('check_in_token', $token)
                          ->with('user', 'temple', 'devotees') // Eager load relationships
                          ->firstOrFail();

        // Pass the booking to a special view designed for scanning.
        return view('check-in.show', compact('booking'));
    }

    /**
     * Mark the booking as 'Checked-In'. This is the "disable" action.
     */
    public function confirm($token)
    {
        $booking = Booking::where('check_in_token', $token)->firstOrFail();

        // Prevent double check-ins
        if ($booking->status === 'Checked-In') {
            return back()->with('error', 'This ticket has already been used.');
        }

        // Update the booking status
        $booking->status = 'Checked-In';
        $booking->checked_in_at = now();
        $booking->save();

        return back()->with('success', 'Check-in Successful!');
    }
}
