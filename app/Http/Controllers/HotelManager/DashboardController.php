<?php

namespace App\Http\Controllers\HotelManager;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\StayBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard for the hotel manager.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $manager = Auth::user();
        $hotel = Hotel::where('manager_id', $manager->id)->first();

        if (!$hotel) {
            return view('hotel-manager.dashboard', [
                'error' => 'You are not currently assigned to manage a hotel.'
            ]);
        }

        // Get the IDs of all rooms belonging to the manager's hotel
        $roomIds = $hotel->rooms()->pluck('id');

        // Fetch the 5 most recent stay bookings for those rooms
        $recentBookings = StayBooking::whereIn('room_id', $roomIds)
            ->with('user', 'room')
            ->latest()
            ->take(5)
            ->get();

        return view('hotel-manager.dashboard', compact('hotel', 'recentBookings'));
    }
}
