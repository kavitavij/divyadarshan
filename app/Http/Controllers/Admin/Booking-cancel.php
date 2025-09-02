<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingCancelController extends Controller
{
    // List cancelled bookings
    public function index()
    {
        $bookings = Booking::where('status', 'cancelled')
            ->with(['user', 'temple'])
            ->orderBy('cancelled_at', 'desc')
            ->paginate(10);

        return view('admin.booking-cancel.index', compact('bookings'));
    }

    // Show single cancelled booking
    public function show($id)
    {
        $booking = Booking::with(['user', 'temple'])->findOrFail($id);

        if ($booking->status !== 'cancelled') {
            abort(404, 'Booking is not cancelled');
        }

        return view('admin.booking-cancel.show', compact('booking'));
    }
}
