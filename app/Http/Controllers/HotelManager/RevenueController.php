<?php

namespace App\Http\Controllers\HotelManager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\StayBooking;
use Carbon\Carbon;

class RevenueController extends Controller
{
    public function index(Request $request)
    {
        $manager = Auth::user();

        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        // Default to the current month if no dates are provided
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        // Base Query: Get bookings only from hotels managed by the current user.
        $bookingsQuery = StayBooking::whereHas('hotel', function ($query) use ($manager) {
            $query->where('manager_id', $manager->id);
        })
        ->whereIn('status', ['completed', 'Confirmed', 'confirmed']);
        // Clone the query to calculate stats over the whole period
        $statsQuery = clone $bookingsQuery;

        // Apply date filters to the query for the detailed list
        $bookingsQuery->whereBetween('check_in_date', [$startDate, $endDate]);

        // --- Calculate Metrics for the selected date range ---
        $totalRevenue = (clone $statsQuery)->whereBetween('check_in_date', [$startDate, $endDate])->sum('total_amount');
        $totalBookings = (clone $statsQuery)->whereBetween('check_in_date', [$startDate, $endDate])->count();
        $averageBookingValue = ($totalBookings > 0) ? $totalRevenue / $totalBookings : 0;

        // Get the paginated list of bookings
        $bookings = $bookingsQuery->with('user', 'hotel')->latest()->paginate(15);

        return view('hotel-manager.revenue.index', [
            'totalRevenue' => $totalRevenue,
            'totalBookings' => $totalBookings,
            'averageBookingValue' => $averageBookingValue,
            'bookings' => $bookings,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }
}
