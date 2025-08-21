<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\AccommodationBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StayController extends Controller
{
    /**
     * Display a listing of all available hotels.
     */
    public function index()
    {
        $hotels = Hotel::latest()->paginate(12);
        return view('stays.index', compact('hotels'));
    }

    /**
     * Display the specified hotel and its available rooms.
     */
    public function show(Hotel $hotel)
    {
        $hotel->load('rooms');
        return view('stays.show', compact('hotel'));
    }

    /**
     * Show the form for entering booking details (check-in, etc.).
     */
    public function details(Room $room)
    {
        return view('stays.details', compact('room'));
    }

    /**
     * Store the booking and redirect to the summary page.
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
        $totalAmount = $nights * $room->price_per_night;

        $booking = AccommodationBooking::create([
            'user_id' => Auth::id(),
            'room_id' => $room->id,
            'check_in_date' => $request->check_in_date,
            'check_out_date' => $request->check_out_date,
            'number_of_guests' => $request->number_of_guests,
            'total_amount' => $totalAmount,
            'status' => 'Pending Payment',
        ]);

        return redirect()->route('stays.summary', $booking);
    }

    /**
     * Display the booking summary.
     */
    public function summary(AccommodationBooking $booking)
    {
        $booking->load('room.hotel');
        return view('stays.summary', ['stayBooking' => $booking]);
    }
}
