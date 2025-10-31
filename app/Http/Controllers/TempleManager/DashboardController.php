<?php

namespace App\Http\Controllers\TempleManager;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\SevaBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $manager = Auth::user();
        $temple = $manager->temple;

        if (!$temple) {
            return view('temple-manager.dashboard', [
                'temple' => null,
                'error' => 'You are not assigned to any temple. Please contact the administrator.',
            ]);
        }

        // --- Date Filtering ---
        $startDate = $request->input('start_date')
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : Carbon::now()->subDays(29)->startOfDay();

        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))->endOfDay()
            : Carbon::now()->endOfDay();

        // --- Base Queries for the selected period ---
        $darshanQueryForPeriod = Booking::where('temple_id', $temple->id)
            ->whereBetween('created_at', [$startDate, $endDate]);

        $sevaQueryForPeriod = SevaBooking::whereHas('seva', function ($q) use ($temple) {
            $q->where('temple_id', $temple->id);
        })->whereBetween('created_at', [$startDate, $endDate]);

        // --- Calculate Metrics for the period ---
        $revenueForPeriod = (clone $sevaQueryForPeriod)
            ->where('status', 'confirmed')
            ->sum('amount');

        $darshanBookingsForPeriod = (clone $darshanQueryForPeriod)->count();
        $sevaBookingsForPeriod = (clone $sevaQueryForPeriod)->count();
        $totalBookingsForPeriod = $darshanBookingsForPeriod + $sevaBookingsForPeriod;

        $bookingsForPeriod = $totalBookingsForPeriod;

        $allTimeBookingCount =
            Booking::where('temple_id', $temple->id)->count() +
            SevaBooking::whereHas('seva', fn($q) => $q->where('temple_id', $temple->id))->count();

        // --- Recent Bookings (in period) ---
        $recentDarshanBookings = Booking::where('temple_id', $temple->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with(['user', 'order'])
            ->latest()
            ->take(5)
            ->get();

        $recentSevaBookings = SevaBooking::whereHas('seva', function ($q) use ($temple) {
            $q->where('temple_id', $temple->id);
        })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with(['user', 'order', 'seva'])
            ->latest()
            ->take(5)
            ->get();

        // --- Chart Data Preparation ---
        $labels = [];
        $revenueData = [];
        $bookingsData = [];
        $diffInDays = $startDate->diffInDays($endDate);

        if ($diffInDays > 60) {
            $dateFormat = 'M Y';
            $dbFormat = '%Y-%m';
            $period = new \DatePeriod(
                $startDate->copy()->startOfMonth(),
                new \DateInterval('P1M'),
                $endDate->copy()->endOfMonth()
            );
        } else {
            $dateFormat = 'd M';
            $dbFormat = '%Y-%m-%d';
            $period = new \DatePeriod($startDate, new \DateInterval('P1D'), $endDate);
        }

        foreach ($period as $date) {
            $labels[] = $date->format($dateFormat);
            $key = $date->format(str_replace('%', '', $dbFormat));
            $revenueData[$key] = 0;
            $bookingsData[$key] = 0;
        }

        // Revenue data (Seva)
        $periodRevenue = SevaBooking::whereHas('seva', function ($q) use ($temple) {
            $q->where('temple_id', $temple->id);
        })
            ->where('status', 'confirmed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw("DATE_FORMAT(created_at, '{$dbFormat}') as date, SUM(amount) as revenue")
            ->groupBy('date')
            ->pluck('revenue', 'date');

        // Bookings data (Darshan + Seva)
        $periodDarshanBookings = (clone $darshanQueryForPeriod)
            ->selectRaw("DATE_FORMAT(created_at, '{$dbFormat}') as date, COUNT(*) as bookings")
            ->groupBy('date')
            ->pluck('bookings', 'date');

        $periodSevaBookings = (clone $sevaQueryForPeriod)
            ->selectRaw("DATE_FORMAT(created_at, '{$dbFormat}') as date, COUNT(*) as bookings")
            ->groupBy('date')
            ->pluck('bookings', 'date');

        foreach ($revenueData as $date => &$value) {
            $value = $periodRevenue[$date] ?? 0;
        }

        foreach ($bookingsData as $date => &$value) {
            $value = ($periodDarshanBookings[$date] ?? 0) + ($periodSevaBookings[$date] ?? 0);
        }

        $chartData = json_encode([
            'labels' => $labels,
            'revenue' => array_values($revenueData),
            'bookings' => array_values($bookingsData),
        ]);

        // --- Return View ---
        return view('temple-manager.dashboard', compact(
            'temple',
            'revenueForPeriod',
            'totalBookingsForPeriod',
            'darshanBookingsForPeriod',
            'sevaBookingsForPeriod',
            'recentDarshanBookings',
            'recentSevaBookings',
            'chartData',
            'startDate',
            'endDate',
            'bookingsForPeriod',
            'allTimeBookingCount'
        ));
    }
}
