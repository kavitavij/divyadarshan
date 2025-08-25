<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Room;
// THE FIX: Changed from AccommodationBooking to the correct StayBooking model
use App\Models\StayBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StayController extends Controller
{
    /**
     * Display a listing of the hotels.
     */
    public function index()
    {
        $hotels = Hotel::with('rooms')->where('is_approved', true)->paginate(10);
        return view('stays.index', compact('hotels'));
    }

    /**
     * Display the specified hotel.
     */
    public function show(Hotel $hotel)
    {
        $hotel->load('rooms');
        return view('stays.show', compact('hotel'));
    }

    /**
     * Show the form for creating a new booking.
     */
    public function details(Room $room)
    {
        return view('stays.details', compact('room'));
    }

    /**
     * Store a newly created booking in storage.
     */
    public function store(Request $request, Room $room)
    {
        $request->validate([
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'number_of_guests' => 'required|integer|min:1|max:' . $room->capacity,
        ]);

        $checkIn = Carbon::parse($request->check_in_date);
        $checkOut = Carbon::parse($request->check_out_date);
        $nights = $checkOut->diffInDays($checkIn);

        if ($nights < 1) {
            return back()->with('error', 'The check-out date must be after the check-in date.');
        }

        // THE FIX: Using the correct StayBooking model here
        $booking = StayBooking::create([
            'user_id' => Auth::id(),
            'room_id' => $room->id,
            'check_in_date' => $request->check_in_date,
            'check_out_date' => $request->check_out_date,
            'number_of_guests' => $request->number_of_guests,
            'total_amount' => $nights * $room->price_per_night,
            'status' => 'Pending Payment',
        ]);

        return redirect()->route('payment.create', ['type' => 'stay', 'id' => $booking->id]);
    }
}
