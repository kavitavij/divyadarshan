<?php

namespace App\Http\Controllers\TempleManager;

use App\Http\Controllers\Controller;
use App\Models\Temple;
use App\Models\Booking;
use App\Models\SevaBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $temple = Temple::where('manager_id', Auth::id())->first();

        if (!$temple) {
            return view('temple-manager.dashboard', [
                'error' => 'You are not currently assigned to manage a temple. Please contact the administrator.'
            ]);
        }

        // Fetch recent Darshan bookings (for sidebar/cards)
        $darshanBookings = Booking::where('temple_id', $temple->id)
    ->with(['user', 'slot'])
    ->latest()
    ->take(5)
    ->get();

        // Fetch recent Seva bookings (for sidebar/cards)
        $sevaBookings = SevaBooking::whereHas('seva', function ($query) use ($temple) {
            $query->where('temple_id', $temple->id);
        })
            ->with('user', 'seva')
            ->latest()
            ->take(5)
            ->get();

        // Fetch recent darshan bookings for the table (more detailed view)
        $bookings = Booking::where('temple_id', $temple->id)
            ->with(['user', 'slot'])
            ->latest()
            ->take(10)
            ->get();

        // Optional: Combine Darshan and Seva for future use
        $recentBookings = $darshanBookings->toBase()
            ->merge($sevaBookings)
            ->sortByDesc('created_at');

        return view('temple-manager.dashboard', compact(
            'temple',
            'darshanBookings',
            'sevaBookings',
            'bookings',
            'recentBookings'
        ));
    }
}
