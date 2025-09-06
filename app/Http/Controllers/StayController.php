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
        // Start a base query for hotels
        $query = Hotel::query()->with('temple');

        // 1. Conditionally apply the search term filter
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            // Search by hotel name OR location
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('location', 'like', "%{$searchTerm}%");
            });
        }

        // 2. Conditionally apply the temple filter
        if ($request->filled('temple_id')) {
            $query->where('temple_id', $request->input('temple_id'));
        }

        // Eager load the minimum price for each hotel's rooms
        // This is an efficient way to show "Prices from..."
        $query->withMin('rooms', 'price_per_night');

        // Paginate the results
        $hotels = $query->latest()->paginate(9);

        // 3. Append the filter criteria to pagination links
        // This ensures filters are not lost when you go to page 2, 3, etc.
        $hotels->appends($request->all());

        // Get all temples to populate the filter dropdown
        $temples = Temple::orderBy('name')->get();
        $amenities = \App\Models\Amenity::orderBy('name')->get();
        // Pass the hotels and temples to the view
        return view('stays.index', [
            'hotels' => $hotels,
            'temples' => $temples,
            'amenities' => $amenities,
        ]);
    }

    public function show(Hotel $hotel)
    {
        // ** THE FIX IS HERE **
        // We use nested eager loading 'rooms.photos' to get the photos for each room.
        $hotel->load(['rooms.photos', 'temple', 'reviews.user', 'images', 'amenities']);

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
        // The 'with()' efficiently loads the hotel information along with the room
        $room->load('hotel');
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
