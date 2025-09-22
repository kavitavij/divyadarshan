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
            return view('temple-manager.dashboard', [
                'temple' => null,
                'bookings' => collect(),
                'darshanBookings' => collect(),
                'sevaBookings' => collect(),
            ]);
        }

        $allDarshanBookings = Booking::where('temple_id', $temple->id)
            ->with(['user', 'darshanSlot', 'defaultDarshanSlot']) 
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
            'bookings'        => $allDarshanBookings, 
            'darshanBookings' => $allDarshanBookings->take(5), 
            'sevaBookings'    => $sevaBookings,
        ]);
    }
}



