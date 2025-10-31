<?php

namespace App\Http\Controllers\HotelManager;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\StayBooking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    /**
     * Show the application dashboard for the hotel manager.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $manager = Auth::user();
        $hotel = Hotel::where('manager_id', $manager->id)->first();

        if (!$hotel) {
            return view('hotel-manager.dashboard', [
                'hotel' => null,
                'totalRevenue' => 0,
                'totalBookings' => 0,
                'totalRooms' => 0,
                'recentBookings' => collect(),
                'revenueForPeriod' => 0,
                'bookingsForPeriod' => 0,
                'chartData' => json_encode(['labels' => [], 'revenue' => [], 'bookings' => []]),
                'startDate' => Carbon::now()->subDays(29),
                'endDate' => Carbon::now(),
            ]);
        }

        // --- Date Filtering ---
        $startDate = $request->filled('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : Carbon::now()->subDays(29)->startOfDay();
        $endDate = $request->filled('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::now()->endOfDay();

        // Base query for bookings of this hotel within the date range
        $bookingsQueryForPeriod = StayBooking::where('hotel_id', $hotel->id)
            ->whereBetween('created_at', [$startDate, $endDate]);

        // --- Calculate Metrics ---
        // 1. Revenue & Bookings for the selected period
        $revenueForPeriod = (clone $bookingsQueryForPeriod)->where('status', 'completed')->sum('total_amount');
        $bookingsForPeriod = (clone $bookingsQueryForPeriod)->count();

        // Base query for ALL bookings for this hotel (not limited by date)
        $bookingsQuery = StayBooking::where('hotel_id', $hotel->id);

        // All-time stats can be added here if needed, but for now we focus on the period

        // 3. Total number of rooms
        $totalRooms = $hotel->rooms()->count();

        // 4. Get 5 most recent bookings
        $recentBookings = (clone $bookingsQuery)
            ->with('user', 'room')
            ->latest()
            ->take(5)
            ->get();

        // --- Prepare Chart Data for the selected period ---
        $labels = [];
        $revenueData = [];
        $bookingsData = [];
        $diffInDays = $startDate->diffInDays($endDate);

        // Group by month if range is > 60 days, otherwise group by day
        if ($diffInDays > 60) {
            $dateFormat = 'M Y';
            $dbFormat = '%Y-%m';
            $period = new \DatePeriod($startDate->copy()->startOfMonth(), new \DateInterval('P1M'), $endDate->copy()->endOfMonth());
        } else {
            $dateFormat = 'd M';
            $dbFormat = '%Y-%m-%d';
            $period = new \DatePeriod($startDate, new \DateInterval('P1D'), $endDate);
        }

        foreach ($period as $date) {
            $labels[] = $date->format($dateFormat);
            $revenueData[$date->format(str_replace('%', '', $dbFormat))] = 0;
            $bookingsData[$date->format(str_replace('%', '', $dbFormat))] = 0;
        }

        // Fetch revenue data
        $periodRevenue = StayBooking::where('hotel_id', $hotel->id)
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw("DATE_FORMAT(created_at, '{$dbFormat}') as date, SUM(total_amount) as revenue")
            ->groupBy('date')->pluck('revenue', 'date');

        // Fetch bookings data
        $periodBookings = (clone $bookingsQueryForPeriod)
            ->selectRaw("DATE_FORMAT(created_at, '{$dbFormat}') as date, COUNT(*) as bookings")
            ->groupBy('date')->pluck('bookings', 'date');

        // Populate the data arrays
        foreach ($revenueData as $date => &$value) { $value = $periodRevenue[$date] ?? 0; }
        foreach ($bookingsData as $date => &$value) { $value = $periodBookings[$date] ?? 0; }

        $chartData = json_encode(['labels' => $labels, 'revenue' => array_values($revenueData), 'bookings' => array_values($bookingsData)]);

        return view('hotel-manager.dashboard', compact('hotel', 'recentBookings', 'revenueForPeriod', 'bookingsForPeriod', 'totalRooms', 'chartData', 'startDate', 'endDate'));
    }
}
