<?php

namespace App\Http\Controllers\HotelManager;

use App\Http\Controllers\Controller;
use App\Models\StayBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Hotel; 

class GuestListController extends Controller
{
    /**
     * Display a listing of the accommodation bookings for the manager's hotel.
     */
    public function index()
    {
        $manager = Auth::user();

        $hotel = Hotel::where('manager_id', $manager->id)->first();

        if (!$hotel) {
            return redirect()->route('hotel-manager.dashboard')->with('error', 'You are not assigned to a hotel.');
        }

        $roomIds = $hotel->rooms()->pluck('id');

        $bookings = StayBooking::whereIn('room_id', $roomIds)
        ->with(['user', 'room', 'order']) // Eager load relationships for efficiency
            ->latest()
            ->paginate(15);

        return view('hotel-manager.guest-list.index', compact('hotel', 'bookings'));
    }
}
