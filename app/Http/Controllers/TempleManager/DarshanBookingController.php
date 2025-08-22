<?php

namespace App\Http\Controllers\TempleManager;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DarshanBookingController extends Controller
{
    /**
     * Display a listing of the darshan bookings for the manager's temple.
     */
    public function index()
    {
        $manager = Auth::user();
        $temple = $manager->temple;

        if (!$temple) {
            return redirect()->route('temple-manager.dashboard')->with('error', 'You are not assigned to a temple.');
        }

        // Fetch only the Darshan bookings for this specific temple
        $bookings = Booking::where('temple_id', $temple->id)
            ->with('user') // Eager load user details for efficiency
            ->latest()
            ->paginate(15);

        return view('temple-manager.darshan-bookings.index', compact('temple', 'bookings'));
    }
}
