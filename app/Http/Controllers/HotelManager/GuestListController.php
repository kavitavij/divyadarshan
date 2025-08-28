<?php

namespace App\Http\Controllers\HotelManager;

use App\Http\Controllers\Controller;
use App\Models\StayBooking; // CORRECTED: Changed model to StayBooking
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Hotel; // It's good practice to import the Hotel model

class GuestListController extends Controller
{
    /**
     * Display a listing of the accommodation bookings for the manager's hotel.
     */
    public function index()
    {
        $manager = Auth::user();

        // A more robust way to find the hotel managed by the current user
        $hotel = Hotel::where('manager_id', $manager->id)->first();

        if (!$hotel) {
            return redirect()->route('hotel-manager.dashboard')->with('error', 'You are not assigned to a hotel.');
        }

        // Get the IDs of all rooms belonging to the manager's hotel
        $roomIds = $hotel->rooms()->pluck('id');

        // CORRECTED: Fetch all bookings from the StayBooking model that are for those rooms
        $bookings = StayBooking::whereIn('room_id', $roomIds)
            ->with('user', 'room') // Eager load relationships for efficiency
            ->latest()
            ->paginate(15);

        return view('hotel-manager.guest-list.index', compact('hotel', 'bookings'));
    }
}
