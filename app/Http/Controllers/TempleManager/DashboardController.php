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
        $temple = Auth::user()->temple;

        if (!$temple) {
            // Pass null for the temple if the manager is not assigned to one.
            // The view should handle this case gracefully.
            return view('temple-manager.dashboard')->with([
                'temple' => null,
                'darshanBookings' => collect(),
                'sevaBookings' => collect(),
                'bookings' => collect(),
            ]);
        }

        // Fetch recent Darshan bookings for the dashboard cards
        $darshanBookings = Booking::where('temple_id', $temple->id)
            ->with(['user', 'slot'])
            ->latest()
            ->take(5)
            ->get();

        // Fetch recent Seva bookings for the dashboard cards
        $sevaBookings = SevaBooking::whereHas('seva', function ($query) use ($temple) {
            $query->where('temple_id', $temple->id);
        })
            ->with('user', 'seva')
            ->latest()
            ->take(5)
            ->get();

        // Fetch all-time bookings for the count widget
        $allTimeBookings = Booking::where('temple_id', $temple->id)->get();

        return view('temple-manager.dashboard', [
            'temple'          => $temple,
            'darshanBookings' => $darshanBookings,
            'sevaBookings'    => $sevaBookings,
            'bookings'        => $allTimeBookings, // Used for the "All-Time" widget count
        ]);
    }
}

