<?php

namespace App\Http\Controllers\TempleManager;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\SevaBooking;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $temple = Auth::user()->temple;

        if (!$temple) {
            // Pass empty collections to the view if no temple is assigned
            return view('temple-manager.dashboard', [
                'temple' => null,
                'bookings' => collect(),
                'darshanBookings' => collect(),
                'sevaBookings' => collect(),
            ]);
        }

        // --- THE FIX IS HERE ---
        // 1. Fetch all Darshan bookings for the temple in a single, efficient query.
        // 2. Eager-load the ACTUAL relationships (`darshanSlot`, `defaultDarshanSlot`).
        //    The `slot` and `slot_time` accessors in your Booking model will then use this pre-loaded data.
        $allDarshanBookings = Booking::where('temple_id', $temple->id)
            ->with(['user', 'darshanSlot', 'defaultDarshanSlot']) // CORRECT EAGER LOADING
            ->latest()
            ->get();

        // Fetch recent Seva bookings for the summary card
        $sevaBookings = SevaBooking::whereHas('seva', function ($query) use ($temple) {
            $query->where('temple_id', $temple->id);
        })
        ->with(['user', 'seva'])
        ->latest()
        ->take(5)
        ->get();

        return view('temple-manager.dashboard', [
            'temple'          => $temple,
            'bookings'        => $allDarshanBookings, // Used for the main table and "All-Time" count
            'darshanBookings' => $allDarshanBookings->take(5), // Take the 5 most recent for the summary card
            'sevaBookings'    => $sevaBookings,
        ]);
    }
}

