<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Donation;
use App\Models\StayBooking;
use App\Models\SevaBooking;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RevenueExport;
class AdminRevenueController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ]);

        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate   = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        // 1. Darshan Revenue
        $darshanRevenue = Booking::where('bookings.status', 'Confirmed')
            ->whereBetween('booking_date', [$startDate, $endDate])
            ->join('temples', 'bookings.temple_id', '=', 'temples.id')
            ->sum(DB::raw('bookings.number_of_people * temples.darshan_charge'));
        // 2. Donation Revenue
        $donationRevenue = Donation::where('status', 'Completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        // 3. Hotel Stay Revenue
        $stayRevenue = StayBooking::whereIn('status', ['Confirmed', 'completed'])
            ->whereBetween('check_in_date', [$startDate, $endDate])
            ->sum('total_amount');

        // 4. Seva Revenue
        $sevaRevenue = SevaBooking::where('status', 'Completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        // 5. Ebook Revenue (from Order details)
        $orders = Order::where('status', 'Completed')->whereBetween('created_at', [$startDate, $endDate])->get();
        $ebookRevenue = $orders->sum(function ($order) {
            $details = $order->order_details ?? [];
            $ebookTotal = 0;
            foreach ($details as $item) {
                if (isset($item['type']) && $item['type'] === 'ebook') {
                    $ebookTotal += ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
                }
            }
            return $ebookTotal;
        });

        // Grand Total
        $totalRevenue = $darshanRevenue + $donationRevenue + $stayRevenue + $sevaRevenue + $ebookRevenue;

        // --- GET RECENT TRANSACTIONS FOR DISPLAY ---
        $darshanBookings = Booking::where('bookings.status', 'Confirmed')
            ->whereBetween('booking_date', [$startDate, $endDate])
            ->join('temples', 'bookings.temple_id', '=', 'temples.id')
            ->select('bookings.*', 'temples.name as temple_name', DB::raw('bookings.number_of_people * temples.darshan_charge as total_amount'))
            ->latest('bookings.created_at')
            ->paginate(5, ['*'], 'darshan_page');
        $donations = Donation::with('temple')->whereBetween('created_at', [$startDate, $endDate])->latest()->paginate(5, ['*'], 'donation_page');
        $stayBookings = StayBooking::with('hotel')->whereBetween('check_in_date', [$startDate, $endDate])->latest()->paginate(5, ['*'], 'stay_page');

        return view('admin.revenue.index', compact(
            'totalRevenue', 'darshanRevenue', 'donationRevenue', 'stayRevenue', 'sevaRevenue', 'ebookRevenue',
            'darshanBookings', 'donations', 'stayBookings',
            'startDate', 'endDate'
        ));
    }
    public function download(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ]);

        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate   = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        // Get ALL transactions for the period (without pagination)
        $darshanBookings = \App\Models\Booking::where('bookings.status', 'Confirmed')
            ->whereBetween('booking_date', [$startDate, $endDate])
            ->join('temples', 'bookings.temple_id', '=', 'temples.id')
            ->select('bookings.*', DB::raw('bookings.number_of_people * temples.darshan_charge as total_amount'))
            ->get();

        $stayBookings = \App\Models\StayBooking::with('hotel', 'room')
            ->whereIn('status', ['Confirmed', 'completed'])
            ->whereBetween('check_in_date', [$startDate, $endDate])
            ->get();

        $sevaBookings = \App\Models\SevaBooking::with('seva.temple')
            ->where('status', 'Completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $donations = \App\Models\Donation::with('temple')
            ->where('status', 'Completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $data = [
            'darshanBookings' => $darshanBookings,
            'stayBookings'    => $stayBookings,
            'sevaBookings'    => $sevaBookings,
            'donations'       => $donations,
        ];

        $fileName = 'revenue_report_' . $startDate . '_to_' . $endDate . '.xlsx';

        return Excel::download(new RevenueExport($data), $fileName);
    }
}

