<?php

namespace App\Http\Controllers\TempleManager;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\SevaBooking;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // Make sure Carbon is imported

class DashboardController extends Controller
{
    public function index()
    {
        $temple = Auth::user()->temple;

        if (!$temple) {
            return view('temple-manager.dashboard', [
                'temple' => null,
                'bookings' => collect(),
                'darshanBookings' => collect(),
                'sevaBookings' => collect(),
                'allTimeBookingCount' => 0,
            ]);
        }
        // 1. Get ALL Darshan bookings
        $allDarshanBookings = Booking::where('temple_id', $temple->id)
            ->with(['user', 'darshanSlot', 'defaultDarshanSlot'])
            ->latest()
            ->get();

        // 2. Filter Darshan bookings for the last 24 hours
        $recentDarshanBookings = $allDarshanBookings->where('created_at', '>=', Carbon::now()->subDay());

        // 3. (CORRECTED) Get ALL Seva bookings first
        $allSevaBookings = SevaBooking::whereHas('seva', function ($query) use ($temple) {
            $query->where('temple_id', $temple->id);
        })
        ->with(['user', 'seva'])
        ->latest()
        ->get();
        
        // 4. (CORRECTED) Now, filter Seva bookings for the last 24 hours
        $recentSevaBookings = $allSevaBookings->where('created_at', '>=', Carbon::now()->subDay());
        
        // 5. Calculate the accurate all-time total
        $allTimeBookingCount = $allDarshanBookings->count() + $allSevaBookings->count();

        // Pass the variables to the view
        return view('temple-manager.dashboard', [
            'temple'              => $temple,
            'bookings'            => $allDarshanBookings,      
            'darshanBookings'     => $recentDarshanBookings,   
            'sevaBookings'        => $recentSevaBookings,      
            'allTimeBookingCount' => $allTimeBookingCount,      
        ]);
    }
}

