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

class BookingController extends Controller
{
    /**
     * Display a listing of all combined bookings for the manager's temple.
     */
    public function index()
    {
        $temple = Auth::user()->temple;
        if (!$temple) {
            return redirect()->route('temple-manager.dashboard')->with('error', 'You are not assigned to a temple.');
        }

        // Get Darshan bookings, adding a 'type' attribute
        $darshanBookings = Booking::where('temple_id', $temple->id)
            ->with('user')
            ->select('*', DB::raw("'Darshan' as type"))
            ->get();

        // Get Seva bookings, adding a 'type' attribute
        $sevaBookings = SevaBooking::whereHas('seva', function ($query) use ($temple) {
            $query->where('temple_id', $temple->id);
        })->with('user', 'seva')
          ->select('*', DB::raw("'Seva' as type"))
          ->get();

        // Merge, sort, and paginate
        $allBookings = $darshanBookings->concat($sevaBookings)->sortByDesc('created_at');
        $bookings = $this->paginate($allBookings);

        return view('temple-manager.bookings.index', compact('temple', 'bookings'));
    }

    /**
     * Display the specified booking resource (Darshan or Seva).
     *
     * @param string $type ('darshan' or 'seva')
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($type, $id)
    {
        $temple = Auth::user()->temple;
        $booking = null;

        if ($type === 'darshan') {
            $booking = Booking::with(['user', 'temple', 'devotees'])->findOrFail($id);
            // Security check
            if ($booking->temple_id !== $temple->id) {
                abort(403);
            }
        } elseif ($type === 'seva') {
            $booking = SevaBooking::with(['user', 'seva.temple'])->findOrFail($id);
            // Security check
            if ($booking->seva->temple_id !== $temple->id) {
                abort(403);
            }
        } else {
            abort(404);
        }

        return view('temple-manager.bookings.show', compact('booking', 'type'));
    }

    /**
     * Helper function to manually paginate a collection.
     */
    private function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (LengthAwarePaginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof \Illuminate\Support\Collection ? $items : \Illuminate\Support\Collection::make($items);
        $paginator = new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath()
        ]);
        return $paginator;
    }
}
