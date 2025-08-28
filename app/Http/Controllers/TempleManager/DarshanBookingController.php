<?php

namespace App\Http\Controllers\TempleManager;

use App\Http\Controllers\Controller;
use App\Models\Temple;
use App\Models\Booking;
use App\Models\SevaBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class DarshanBookingController extends Controller
{
    /**
     * Display a listing of all bookings for the manager's temple.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Find the temple associated with the current manager
        $temple = Temple::where('manager_id', Auth::id())->firstOrFail();

        // Get all Darshan bookings for this temple, adding a 'type' attribute
        $darshanBookings = Booking::where('temple_id', $temple->id)
            ->with('user')
            ->select('*', DB::raw("'Darshan' as type"))
            ->get();

        // Get all Seva bookings for this temple, adding a 'type' attribute
        $sevaBookings = SevaBooking::whereHas('seva', function ($query) use ($temple) {
            $query->where('temple_id', $temple->id);
        })->with('user', 'seva')
          ->select('*', DB::raw("'Seva' as type"))
          ->get();

        // Merge, sort, and paginate the bookings
        $allBookings = $darshanBookings->concat($sevaBookings)->sortByDesc('created_at');

        $perPage = 15;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentPageItems = $allBookings->slice(($currentPage - 1) * $perPage, $perPage)->all();

        $bookings = new LengthAwarePaginator($currentPageItems, $allBookings->count(), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);

        return view('temple-manager.darshan-bookings.index', compact('temple', 'bookings'));
    }
}
