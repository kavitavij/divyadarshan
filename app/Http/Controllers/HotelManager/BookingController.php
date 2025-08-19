<?php

namespace App\Http\Controllers\HotelManager;

use App\Http\Controllers\Controller;
use App\Models\AccommodationBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Display a listing of the accommodation bookings for the manager's hotel.
     */
    public function index()
    {
        $manager = Auth::user();
        $hotel = $manager->hotel;

        if (!$hotel) {
            return redirect()->route('hotel-manager.dashboard')->with('error', 'You are not assigned to a hotel.');
        }

        // Get the IDs of all rooms belonging to the manager's hotel
        $roomIds = $hotel->rooms()->pluck('id');

        // Fetch all bookings that are for those rooms
        $bookings = AccommodationBooking::whereIn('room_id', $roomIds)
            ->with('user', 'room') // Eager load relationships for efficiency
            ->latest()
            ->paginate(15);
            
        // THE FIX: This now points to the correct view file in the 'hotel-manager' folder.
        return view('hotel-manager.bookings.index', compact('hotel', 'bookings'));
    }
}
