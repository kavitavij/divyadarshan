<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complaint;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalTemples = Temple::count();

        // Correctly count all confirmed bookings
        $totalBookings = Booking::where('status', 'confirmed')->count()
                         + SevaBooking::where('status', 'confirmed')->count()
                         + AccommodationBooking::where('status', 'confirmed')->count();

        $totalHotels = Hotel::count();

        // --- Fetch recent bookings from all types ---

        // 1. Fetch recent Darshan bookings
        $darshanBookings = Booking::with('user', 'temple')->latest()->get()->map(function ($booking) {
            $booking->type = 'Darshan';
            return $booking;
        });

        // 2. Fetch recent Seva bookings
        $sevaBookings = SevaBooking::with('user', 'seva.temple')->latest()->get()->map(function ($booking) {
            $booking->type = 'Seva';
            return $booking;
        });

        // 3. Fetch recent Accommodation bookings
        $accommodationBookings = AccommodationBooking::with('user', 'room.hotel')->latest()->get()->map(function ($booking) {
            $booking->type = 'Accommodation';
            return $booking;
        });

        // 4. Merge, sort, and take the 5 most recent bookings from all types
        $allBookings = $darshanBookings->merge($sevaBookings)->merge($accommodationBookings)->sortByDesc('created_at');
        $recentBookings = $allBookings->take(5);

        return view('admin.bookings.index', compact('totalUsers', 'totalTemples', 'totalBookings', 'totalHotels', 'recentBookings'));
    }
    public function index()
    {
        // Simply return the admin dashboard view.
        return view('admin.dashboard');
    }
}
