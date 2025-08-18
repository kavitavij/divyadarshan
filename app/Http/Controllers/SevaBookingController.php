<?php

namespace App\Http\Controllers;

use App\Models\Seva;
use App\Models\Temple;
use App\Models\SevaBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SevaBookingController extends Controller
{
    /**
     * Display the Seva booking page where users select a temple.
     */
    public function index(Request $request)
    {
        $temples = Temple::orderBy('name')->get();
        $selectedTemple = null;
        $sevas = [];

        if ($request->has('temple_id')) {
            $selectedTemple = Temple::find($request->input('temple_id'));
            if ($selectedTemple) {
                $sevas = $selectedTemple->sevas()->get();
            }
        }

        return view('sevas.booking', compact('temples', 'selectedTemple', 'sevas'));
    }

    /**
     * Store the initial Seva booking and redirect to the summary page.
     */
    public function store(Request $request)
    {
        $request->validate(['seva_id' => 'required|exists:sevas,id']);

        $seva = Seva::findOrFail($request->seva_id);

        $sevaBooking = SevaBooking::create([
            'user_id' => Auth::id(),
            'seva_id' => $seva->id,
            'amount' => $seva->price,
            'status' => 'Pending Payment',
        ]);

        return redirect()->route('sevas.booking.summary', $sevaBooking);
    }

    /**
     * Display the booking summary page.
     */
    public function summary(SevaBooking $sevaBooking)
    {
        $sevaBooking->load('seva.temple');
        return view('sevas.summary', compact('sevaBooking'));
    }

    /**
     * Display the payment page.
     */
    public function payment(SevaBooking $sevaBooking)
    {
        $sevaBooking->load('seva.temple');
        return view('sevas.payment', compact('sevaBooking'));
    }

    /**
     * Confirm the booking after "payment".
     */
    public function confirm(Request $request)
    {
        $request->validate(['booking_id' => 'required|exists:seva_bookings,id']);
        
        $sevaBooking = SevaBooking::findOrFail($request->booking_id);
        $sevaBooking->status = 'Confirmed';
        $sevaBooking->save();

        return redirect()->route('home')->with('success', 'Your Seva has been booked successfully!');
    }
}
