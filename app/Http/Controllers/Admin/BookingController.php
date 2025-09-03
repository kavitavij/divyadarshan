<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\SevaBooking;
use App\Models\StayBooking;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Hotel;

class BookingController extends Controller
{
    /**
     * Display a listing of all combined bookings with filters.
     *
     * @return \Illuminate\View\View
     */
   // In app/Http/Controllers/Admin/BookingController.php

public function index(Request $request)
{
    $filterType = $request->input('type');
    $filterDate = $request->input('date');

    // Darshan Bookings Query
    $darshanQuery = DB::table('bookings')
        ->join('users', 'bookings.user_id', '=', 'users.id')
        ->join('temples', 'bookings.temple_id', '=', 'temples.id')
        ->select(
            'bookings.id',
            'bookings.status',
            'bookings.created_at',
            DB::raw("'Darshan' as type"),
            'temples.name as location_name',
            'users.name as user_name'
        );

    // Seva Bookings Query
    $sevaQuery = DB::table('seva_bookings')
        ->join('users', 'seva_bookings.user_id', '=', 'users.id')
        ->join('sevas', 'seva_bookings.seva_id', '=', 'sevas.id')
        ->join('temples', 'sevas.temple_id', '=', 'temples.id')
        ->select(
            'seva_bookings.id',
            'seva_bookings.status',
            'seva_bookings.created_at',
            DB::raw("'Seva' as type"),
            'temples.name as location_name',
            'users.name as user_name'
        );

    // The StayBookings query has been removed.

    if ($filterDate) {
        $darshanQuery->whereDate('bookings.created_at', $filterDate);
        $sevaQuery->whereDate('seva_bookings.created_at', $filterDate);
    }

    // Simplified query combination
    if ($filterType === 'Seva') {
        $query = $sevaQuery;
    } elseif ($filterType === 'Darshan') {
        $query = $darshanQuery;
    } else {
        // Default to showing both Darshan and Seva
        $query = $darshanQuery->unionAll($sevaQuery);
    }

    $bookings = $query->orderBy('created_at', 'desc')->paginate(15);

    return view('admin.bookings.index', compact('bookings'));
}
    public function show($type, $id)
    {
        // Use a match expression to handle different booking types
        $booking = match (ucfirst($type)) {
            'Darshan' => Booking::with('devotees', 'temple', 'user')->findOrFail($id),
            'Seva' => SevaBooking::with('user', 'seva.temple')->findOrFail($id),
            'Accommodation' => StayBooking::with('user', 'room.hotel', 'guests')->findOrFail($id),
            default => abort(404, 'Booking type not found.'),
        };

        // Pass the booking and its type to a single view
        return view('admin.bookings.show', [
            'booking' => $booking,
            'type' => ucfirst($type) // Pass the type to the view
        ]);
    }
    public function accommodationIndex(Request $request)
    {
        $filterHotel = $request->input('hotel_id');
        $filterDate = $request->input('date');

        // Start the query on the StayBooking model
        $query = StayBooking::with('user', 'room.hotel')
                            ->orderBy('check_in_date', 'desc');

        // Apply hotel filter if provided
        if ($filterHotel) {
            $query->where('hotel_id', $filterHotel);
        }

        // Apply date filter if provided (checks if the date is between check-in and check-out)
        if ($filterDate) {
            $query->whereDate('check_in_date', '<=', $filterDate)
                  ->whereDate('check_out_date', '>=', $filterDate);
        }

        // Fetch all hotels for the filter dropdown
        $hotels = Hotel::orderBy('name')->get();

        // Paginate the results
        $bookings = $query->paginate(15);

        return view('admin.bookings.accommodation-index', compact('bookings', 'hotels', 'filterHotel', 'filterDate'));
    }
    public function showAccommodation(StayBooking $booking)
    {
        // Eager load all necessary relationships for the view
        $booking->load('user', 'room.hotel', 'guests');

        // Return a new, dedicated view for accommodation bookings
        return view('admin.bookings.show-accommodation', compact('booking'));
    }
}
