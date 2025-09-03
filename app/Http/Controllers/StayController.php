<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\StayBooking;
use App\Models\StayBookingGuest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StayController extends Controller
{
    public function index()
    {
        $hotels = Hotel::with('rooms')->paginate(10);
        return view('stays.index', compact('hotels'));
    }

    public function show(Hotel $hotel)
    {
        $hotel->load('rooms');
        return view('stays.show', compact('hotel'));
    }

    public function details(Room $room)
    {
        return view('stays.details', compact('room'));
    }

    /**
     * Store a newly created stay booking in storage.
     * NEW METHOD
     */
    public function store(Request $request)
    {
        // 1. Validate all incoming data, including the array of guests
        $validatedData = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'number_of_guests' => 'required|integer|min:1',
            'phone_number' => 'required|string|max:15',
            'total_amount' => 'required|numeric',
            'guests' => 'required|array',
            'guests.*.name' => 'required|string|max:255',
            'guests.*.id_type' => 'required|string',
            'guests.*.id_number' => 'required|string|max:255',
        ]);

        $booking = null;

        try {
            // 2. Use a database transaction for safety
            DB::transaction(function () use ($validatedData, &$booking) {
                // 3. Create the main booking record
                $booking = StayBooking::create([
                    'user_id' => auth()->id(),
                    'room_id' => $validatedData['room_id'],
                    'check_in_date' => $validatedData['check_in_date'],
                    'check_out_date' => $validatedData['check_out_date'],
                    'number_of_guests' => $validatedData['number_of_guests'],
                    'phone_number' => $validatedData['phone_number'],
                    'total_amount' => $validatedData['total_amount'],
                    'status' => 'Pending Payment',
                ]);

                // 4. Loop through and create a record for each guest
                foreach ($validatedData['guests'] as $guestData) {
                    $booking->guests()->create([ // Using the relationship to create guests
                        'name' => $guestData['name'],
                        'id_type' => $guestData['id_type'],
                        'id_number' => $guestData['id_number'],
                    ]);
                }
            });
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Booking failed. Please try again.')->withInput();
        }

        // 5. Redirect to the next step (e.g., summary or payment)
        return redirect()->route('booking.summary', ['type' => 'stay', 'id' => $booking->id])
                         ->with('success', 'Booking details saved! Please proceed to payment.');
    }
}
