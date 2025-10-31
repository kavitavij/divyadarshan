<?php

namespace App\Http\Controllers\HotelManager;

use App\Exports\HotelRevenueExport;
use App\Http\Controllers\Controller;
use App\Models\StayBooking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class RevenueController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ]);

        $hotel = Auth::user()->hotel;
        if (!$hotel) {
            return redirect()->route('hotel-manager.dashboard')->with('error', 'You are not assigned to a hotel.');
        }

        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate   = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $query = StayBooking::where('hotel_id', $hotel->id)
            ->whereIn('status', ['Confirmed', 'completed'])
            ->whereBetween('check_in_date', [$startDate, $endDate]);

        // Clone query for calculations before pagination
        $revenueQuery = clone $query;

        $bookings = $query->with(['user', 'hotel'])
            ->latest()
            ->paginate(10)
            ->appends($request->query());

        $totalRevenue = $revenueQuery->sum('total_amount');
        $totalBookings = $revenueQuery->count();
        $averageBookingValue = ($totalBookings > 0) ? $totalRevenue / $totalBookings : 0;

        return view('hotel-manager.revenue.index', compact(
            'bookings',
            'totalRevenue',
            'totalBookings',
            'averageBookingValue',
            'startDate',
            'endDate'
        ));
    }

    public function download(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ]);

        $hotel = Auth::user()->hotel;
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate   = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $bookings = StayBooking::where('hotel_id', $hotel->id)
            ->whereIn('status', ['Confirmed', 'completed'])
            ->whereBetween('check_in_date', [$startDate, $endDate])
            ->with(['user', 'hotel', 'room'])
            ->latest()
            ->get();

        $fileName = 'hotel_revenue_report_' . $startDate . '_to_' . $endDate . '.xlsx';

        return Excel::download(new HotelRevenueExport($bookings), $fileName);
    }
}
