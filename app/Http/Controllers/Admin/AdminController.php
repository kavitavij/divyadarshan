<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Donation;
use App\Models\Hotel;
use App\Models\Order;
use App\Models\StayBooking;
use App\Models\Temple;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard with analytics.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Validate and get dates from request, or set defaults
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : Carbon::now()->subDays(29)->startOfDay();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::now()->endOfDay();

        // === Key Metrics (KPIs) ===
        // Filtered by date range
        $revenueForPeriod = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_amount');
        $bookingsForPeriod = StayBooking::whereBetween('created_at', [$startDate, $endDate])->count()
            + Order::where('status', 'completed')->whereBetween('created_at', [$startDate, $endDate])->count();
        $newUsersForPeriod = User::whereBetween('created_at', [$startDate, $endDate])->count();

        // Overall totals (not filtered by date)
        $totalHotels = Hotel::count();
        $totalTemples = Temple::count();
        $pendingComplaints = Complaint::where('status', 'pending')->count();


        // === Revenue Chart Data (Last 30 days) ===
        $revenueData = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get([
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total')
            ])
            ->pluck('total', 'date');

        // === Bookings Chart Data (Last 30 days) ===
        $bookingData = Order::whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get([
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            ])
            ->pluck('count', 'date');

        // --- Prepare chart data by filling in missing dates ---
        $dates = collect();
        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            $dates->push($date->format('Y-m-d'));
        }

        $revenueChartData = $dates->map(fn ($date) => $revenueData->get($date, 0));
        $bookingChartData = $dates->map(fn ($date) => $bookingData->get($date, 0));

        return view('admin.dashboard', [
            'revenueForPeriod' => $revenueForPeriod,
            'bookingsForPeriod' => $bookingsForPeriod,
            'newUsersForPeriod' => $newUsersForPeriod,
            'totalHotels' => $totalHotels,
            'startDate' => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d'),
            'totalTemples' => $totalTemples,
            'pendingComplaints' => $pendingComplaints,
            'chartLabels' => $dates->map(fn ($date) => Carbon::parse($date)->format('d M')),
            'revenueChartData' => $revenueChartData,
            'bookingChartData' => $bookingChartData,
        ]);
    }
}
