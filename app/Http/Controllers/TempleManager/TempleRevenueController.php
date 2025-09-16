<?php

namespace App\Http\Controllers\TempleManager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Temple;
use App\Models\Booking;
use App\Models\SevaBooking;
use App\Models\Donation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class TempleRevenueController extends Controller
{
    public function index(Request $request)
    {
        $manager = Auth::user();
        $temple = Temple::where('manager_id', $manager->id)->firstOrFail();

        $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ]);

        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate   = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        // 1. Calculate Darshan Revenue
    $darshanRevenue = Booking::where('temple_id', $temple->id)
        ->where('status', 'Confirmed')
        ->whereBetween('booking_date', [$startDate, $endDate])
        ->join('temples', 'bookings.temple_id', '=', 'temples.id')
        ->sum(DB::raw('bookings.number_of_people * temples.darshan_charge'));
        // 2. Calculate Seva Revenue
        $sevaRevenue = SevaBooking::where('status', 'Completed')
            ->whereHas('seva', function ($query) use ($temple) {
                $query->where('temple_id', $temple->id);
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        // 3. Calculate Donation Revenue
        $donationRevenue = Donation::where('temple_id', $temple->id)
            ->where('status', 'Completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        // Calculate Grand Total
        $totalRevenue = $darshanRevenue + $sevaRevenue + $donationRevenue;

        // Get recent transactions for display
        $darshanBookings = Booking::where('bookings.temple_id', $temple->id)
            ->where('bookings.status', 'Confirmed')
            ->whereBetween('booking_date', [$startDate, $endDate])
            ->join('temples', 'bookings.temple_id', '=', 'temples.id')
            ->select('bookings.*', DB::raw('bookings.number_of_people * temples.darshan_charge as total_amount'))
            ->latest('bookings.created_at')
            ->paginate(5, ['*'], 'darshan_page');        $sevaBookings = SevaBooking::whereHas('seva', function ($q) use ($temple) {$q->where('temple_id', $temple->id);})->where('status', 'Completed')->whereBetween('created_at', [$startDate, $endDate])->latest()->paginate(5, ['*'], 'seva_page');
        $donations = Donation::where('temple_id', $temple->id)->where('status', 'Completed')->whereBetween('created_at', [$startDate, $endDate])->latest()->paginate(5, ['*'], 'donation_page');

        return view('temple-manager.revenue.index', compact(
            'totalRevenue', 'darshanRevenue', 'sevaRevenue', 'donationRevenue',
            'darshanBookings', 'sevaBookings', 'donations',
            'startDate', 'endDate', 'temple'
        ));
    }
}
