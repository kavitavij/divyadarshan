<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\SevaBooking;
use App\Models\StayBooking;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display a listing of all combined bookings with filters.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $filterType = $request->input('type');    // Darshan / Seva / Accommodation
        $filterDate = $request->input('date');    // Specific Date

        // 1. Fetch all booking types with relationships
        $darshanBookings = Booking::with('user', 'temple')->get()->map(function ($booking) {
            $booking->type = 'Darshan';
            return $booking;
        });

        $sevaBookings = SevaBooking::with('user', 'seva.temple')->get()->map(function ($booking) {
            $booking->type = 'Seva';
            $booking->amount = $booking->amount;
            return $booking;
        });

        $stayBookings = StayBooking::with('user', 'room.hotel')->get()->map(function ($booking) {
            $booking->type = 'Accommodation';
            $booking->amount = $booking->total_amount;
            return $booking;
        });

        // 2. Merge all collections
        $allBookings = $darshanBookings->concat($sevaBookings)->concat($stayBookings);

        // 3. Apply filters
        if ($filterType) {
            $allBookings = $allBookings->where('type', $filterType);
        }

        if ($filterDate) {
            $allBookings = $allBookings->filter(function ($booking) use ($filterDate) {
                return $booking->created_at->format('Y-m-d') === $filterDate;
            });
        }

        // 4. Sort by date
        $sortedBookings = $allBookings->sortByDesc('created_at');

        // 5. Manual Pagination
        $perPage = 15;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentPageItems = $sortedBookings->slice(($currentPage - 1) * $perPage, $perPage)->all();

        $bookings = new LengthAwarePaginator(
            $currentPageItems,
            $sortedBookings->count(),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        return view('admin.bookings.index', compact('bookings', 'filterType', 'filterDate'));
    }
}
