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
    public function index(Request $request)
    {
        $temple = Auth::user()->temple;
        if (!$temple) {
            return redirect()->route('temple-manager.dashboard')->with('error', 'You are not assigned to a temple.');
        }

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $type = $request->input('type');

        $darshanQuery = Booking::where('temple_id', $temple->id)->with('user');
        if ($startDate) {
            $darshanQuery->whereDate('booking_date', '>=', $startDate);
        }
        if ($endDate) {
            $darshanQuery->whereDate('booking_date', '<=', $endDate);
        }
        $darshanBookings = $darshanQuery
            ->select('*', DB::raw("'Darshan' as type"))
            ->get();

        $sevaQuery = SevaBooking::whereHas('seva', function ($query) use ($temple) {
            $query->where('temple_id', $temple->id);
        })->with('user', 'seva');
        if ($startDate) {
            $sevaQuery->whereDate('booking_date', '>=', $startDate);
        }
        if ($endDate) {
            $sevaQuery->whereDate('booking_date', '<=', $endDate);
        }
        $sevaBookings = $sevaQuery
            ->select('*', DB::raw("'Seva' as type"))
            ->get();

        if ($type === 'darshan') {
            $allBookings = $darshanBookings;
        } elseif ($type === 'seva') {
            $allBookings = $sevaBookings;
        } else {
            $allBookings = $darshanBookings->concat($sevaBookings);
        }

        $allBookings = $allBookings->sortByDesc('created_at');
        $bookings = $this->paginate($allBookings);

        $totalBookings = $darshanBookings->count() + $sevaBookings->count();
        $darshanBookingsCount = $darshanBookings->count();
        $sevaBookingsCount = $sevaBookings->count();

        return view('temple-manager.bookings.index', compact(
            'temple',
            'bookings',
            'totalBookings',
            'darshanBookingsCount',
            'sevaBookingsCount'
        ));
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
