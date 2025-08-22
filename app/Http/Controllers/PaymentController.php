<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Donation;
use App\Models\AccommodationBooking;
use App\Models\SevaBooking;
use App\Models\Booking as DarshanBooking; // Using an alias to prevent class name conflicts

class PaymentController extends Controller
{
    /**
     * Display the universal payment page with a summary.
     */
    public function create($type, $id)
    {
        if (!$type || !$id) {
            abort(404);
        }

        $summary = [];

        // This switch statement figures out what the user is paying for
        switch ($type) {
            case 'donation':
                $donation = Donation::findOrFail($id);
                $summary = [
                    'title' => 'Donation Summary',
                    'item_name' => 'Temple Donation',
                    'amount' => $donation->amount,
                    'type' => 'donation',
                    'id' => $donation->id,
                ];
                break;

            case 'stay':
                $booking = AccommodationBooking::with('room.hotel')->findOrFail($id);
                $summary = [
                    'title' => 'Accommodation Booking Summary',
                    'item_name' => 'Stay at ' . $booking->room->hotel->name,
                    'amount' => $booking->total_amount,
                    'type' => 'stay',
                    'id' => $booking->id,
                ];
                break;

            case 'seva':
                $booking = SevaBooking::with('seva.temple')->findOrFail($id);
                $summary = [
                    'title' => 'Seva Booking Summary',
                    'item_name' => $booking->seva->name . ' at ' . $booking->seva->temple->name,
                    'amount' => $booking->amount,
                    'type' => 'seva',
                    'id' => $booking->id,
                ];
                break;

            case 'darshan':
                $booking = DarshanBooking::with('temple')->findOrFail($id);
                $summary = [
                    'title' => 'Darshan Booking Summary',
                    'item_name' => 'Darshan at ' . $booking->temple->name,
                    'amount' => $booking->total_amount, // Ensure this column exists and is correct
                    'type' => 'darshan',
                    'id' => $booking->id,
                ];
                break;

            default:
                abort(404, 'Invalid payment type.');
        }

        return view('payment.index', compact('summary'));
    }

    /**
     * Confirm the payment after Razorpay success.
     */
    public function confirm(Request $request)
    {
        $validated = $request->validate([
            'payment_id' => 'required|string',
            'order_type' => 'required|string',
            'order_id' => 'required|integer',
        ]);

        // This switch statement updates the correct database record
        switch ($validated['order_type']) {
            case 'donation':
                $order = Donation::findOrFail($validated['order_id']);
                $order->status = 'Completed';
                $order->payment_id = $validated['payment_id'];
                $order->save();
                return redirect()->route('home')->with('success', 'Thank you for your generous donation!');

            case 'stay':
                $order = AccommodationBooking::findOrFail($validated['order_id']);
                $order->status = 'Confirmed';
                $order->payment_id = $validated['payment_id'];
                $order->save();
                return redirect()->route('home')->with('success', 'Your accommodation is confirmed!');

            case 'seva':
                $order = SevaBooking::findOrFail($validated['order_id']);
                $order->status = 'Confirmed';
                $order->payment_id = $validated['payment_id'];
                $order->save();
                return redirect()->route('home')->with('success', 'Your Seva booking is confirmed!');

            case 'darshan':
                $order = DarshanBooking::findOrFail($validated['order_id']);
                $order->status = 'Confirmed';
                $order->payment_id = $validated['payment_id'];
                $order->save();
                return redirect()->route('home')->with('success', 'Your Darshan booking is confirmed!');
        }

        return redirect()->route('home')->with('error', 'Could not confirm your payment.');
    }
}
