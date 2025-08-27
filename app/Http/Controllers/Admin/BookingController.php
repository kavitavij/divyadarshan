<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\SevaBooking;
use App\Models\AccommodationBooking; // Import the AccommodationBooking model
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class BookingController extends Controller
{
    /**
     * Display a combined list of all Darshan, Seva, and Accommodation bookings.
     */
    public function index()
    {
        // 1. Fetch all Darshan bookings with their related user and temple
        $darshanBookings = Booking::with('user', 'temple')->latest()->get()->map(function ($booking) {
            $booking->type = 'Darshan';
            return $booking;
        });

        // 2. Fetch all Seva bookings with their related user, seva, and temple
        $sevaBookings = SevaBooking::with('user', 'seva.temple')->latest()->get()->map(function ($booking) {
            $booking->type = 'Seva';
            return $booking;
        });

        // 3. Fetch all Accommodation bookings with their related user, room, and hotel
        $accommodationBookings = AccommodationBooking::with('user', 'room.hotel')->latest()->get()->map(function ($booking) {
            $booking->type = 'Accommodation';
            return $booking;
        });

        // 4. Merge the three collections and sort them by the creation date
        $allBookings = $darshanBookings->merge($sevaBookings)->merge($accommodationBookings)->sortByDesc('created_at');

        // 5. Manually paginate the combined collection
        $perPage = 15;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentPageItems = $allBookings->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginatedBookings = new LengthAwarePaginator($currentPageItems, count($allBookings), $perPage);
        $paginatedBookings->setPath(request()->url());

        return view('admin.bookings.index', ['bookings' => $paginatedBookings]);
    }
}
