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
     * Display the Seva booking page.
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
     * Display the booking summary.
     */
    public function summary(SevaBooking $sevaBooking)
    {
        $sevaBooking->load('seva.temple');
        return view('sevas.summary', compact('sevaBooking'));
    }

    /**
     * Display the payment page using the shared payment view.
     */
    public function payment(SevaBooking $sevaBooking)
    {
        $sevaBooking->load('seva.temple');

        // Prepare the data for the universal payment page
        $summary = [
            'title' => 'Seva Booking Summary',
            'details' => [
                'Temple' => $sevaBooking->seva->temple->name,
                'Seva' => $sevaBooking->seva->name,
            ],
            'amount' => $sevaBooking->amount,
            'confirm_route' => route('sevas.booking.confirm'),
            'booking_id' => $sevaBooking->id,
        ];

        return view('shared.payment', compact('summary'));
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


    return redirect()->route('payment.create', ['id' => $sevaBooking->id, 'type' => 'seva']);
    }
}
