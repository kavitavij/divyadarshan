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
use App\Models\Temple;
class StayController extends Controller
{
    public function index(Request $request)
    {
        $query = Hotel::query()->with('temple');

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('location', 'like', "%{$searchTerm}%");
            });
        }

        if ($request->filled('temple_id')) {
            $query->where('temple_id', $request->input('temple_id'));
        }

          $query->withMin(['rooms' => function ($query) {
            $query->where('is_visible', true);
        }], DB::raw('price_per_night * (1 - discount_percentage / 100)'), 'rooms_min_discounted_price');


        $hotels = $query->latest()->paginate(9);
        $hotels->appends($request->all());

        $temples = Temple::orderBy('name')->get();
        $amenities = \App\Models\Amenity::orderBy('name')->get();
        return view('stays.index', [
            'hotels' => $hotels,
            'temples' => $temples,
            'amenities' => $amenities,
        ]);
    }

    public function show(Hotel $hotel)
    {
        $hotel->load([
            'rooms' => function ($query) {
                $query->where('is_visible', true)->with('photos');
            },
            'temple',
            'reviews.user',
            'images',
            'amenities'
        ]);

        $averageRating = $hotel->reviews->avg('rating');
        $similarHotels = Hotel::where('location', $hotel->location)
                            ->where('id', '!=', $hotel->id)
                            ->limit(3)
                            ->get();

        return view('stays.show', [
            'hotel' => $hotel,
            'averageRating' => number_format($averageRating, 1),
            'similarHotels' => $similarHotels,
        ]);
    }

    public function details(Room $room)
    {
        if (!$room->is_visible) {
            abort(404);
        }

        $room->load('hotel');
        return view('stays.details', compact('room'));
    }

    /**
     * Store a newly created stay booking in storage.
     * NEW METHOD
     */
    public function store(Request $request)
{
    // 1. REMOVED 'total_amount' from validation. The server will calculate it.
    $validatedData = $request->validate([
        'room_id' => 'required|exists:rooms,id',
        'check_in_date' => 'required|date|after_or_equal:today',
        'check_out_date' => 'required|date|after:check_in_date',
        'number_of_guests' => 'required|integer|min:1',
        'phone_number' => 'required|string|max:15',
        'guests' => 'required|array',
        'guests.*.name' => 'required|string|max:255',
        'guests.*.id_type' => 'required|string',
        'guests.*.id_number' => 'required|string|max:255',
    ]);

    $room = Room::findOrFail($validatedData['room_id']);
    if (!$room->is_visible) {
        return redirect()->back()->with('error', 'This room is not available for booking.')->withInput();
    }

    // 2. SERVER-SIDE PRICE CALCULATION
    $checkIn = Carbon::parse($validatedData['check_in_date']);
    $checkOut = Carbon::parse($validatedData['check_out_date']);
    $nights = $checkIn->diffInDays($checkOut);

    // Ensure the final discounted price is used for the calculation
    $totalAmount = $nights * $room->discounted_price;


    $booking = null;

    try {
        DB::transaction(function () use ($validatedData, $room, $totalAmount, &$booking) {
            $booking = StayBooking::create([
                'user_id' => auth()->id(),
                'room_id' => $validatedData['room_id'],
                'hotel_id' => $room->hotel_id,
                'check_in_date' => $validatedData['check_in_date'],
                'check_out_date' => $validatedData['check_out_date'],
                'number_of_guests' => $validatedData['number_of_guests'],
                'phone_number' => $validatedData['phone_number'],
                'total_amount' => $totalAmount, // 3. Use the secure, server-calculated amount
                'status' => 'Pending Payment',
            ]);

            foreach ($validatedData['guests'] as $guestData) {
                $booking->guests()->create($guestData);
            }
        });
    } catch (\Exception $e) {
        Log::error('Booking failed: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Booking failed. Please try again.')->withInput();
    }

    return redirect()->route('booking.summary', ['type' => 'stay', 'id' => $booking->id])
                     ->with('success', 'Booking details saved! Please proceed to payment.');
}
}

