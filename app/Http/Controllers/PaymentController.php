<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Donation;
use App\Models\SevaBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Display the universal payment page.
     */
    public function show(Request $request)
    {
        $type = $request->query('type');
        $id = $request->query('id');

        if (!$type || !$id) {
            abort(404);
        }

        $summary = [];

        switch ($type) {
            case 'darshan':
                $booking = Booking::with('temple')->findOrFail($id);
                $summary = [
                    'title' => 'Darshan Booking Summary',
                    'details' => [
                        'Temple' => $booking->temple->name,
                        'Number of Devotees' => $booking->number_of_people,
                    ],
                    'amount' => $booking->number_of_people * 100, // Example price
                    'confirm_route' => route('booking.confirm'),
                    'booking_id' => $booking->id,
                ];
                break;

            case 'seva':
                $sevaBooking = SevaBooking::with('seva.temple')->findOrFail($id);
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
                break;

            case 'donation':
                $donation = Donation::findOrFail($id);
                $summary = [
                    'title' => 'Donation Summary',
                    'details' => [
                        'Donation Type' => 'General Temple Fund',
                    ],
                    'amount' => $donation->amount,
                    'confirm_route' => route('donations.confirm'),
                    'donation_id' => $donation->id,
                ];
                break;
        }

        return view('shared.payment', compact('summary'));
    }
}
