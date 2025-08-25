<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Donation;
use App\Models\StayBooking;
use App\Models\Sevabooking;
use App\Models\Booking as DarshanBooking;
use App\Models\Ebook;
use App\Models\Payment;
use Carbon\Carbon;

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

        switch ($type) {
            case 'darshan':
                $booking = DarshanBooking::with('temple')->findOrFail($id);
                $summary = [
                    'title' => 'Darshan Booking Summary',
                    'details' => [
                        'Temple' => $booking->temple->name,
                        'Date' => Carbon::parse($booking->booking_date)->format('d M, Y'),
                        'Devotees' => $booking->number_of_people,
                    ],
                    'amount' => 50.00 * $booking->number_of_people, // Example: ₹50 per person
                    'type' => 'darshan',
                    'id' => $booking->id,
                ];
                break;

            case 'seva':
                $booking = Sevabooking::with('seva.temple')->findOrFail($id);
                $summary = [
                    'title' => 'Seva Booking Summary',
                    'details' => [
                        'Temple' => $booking->seva->temple->name,
                        'Seva' => $booking->seva->name,
                        'Date' => Carbon::parse($booking->booking_date)->format('d M, Y'),
                        'Devotee Name' => $booking->devotee_name,
                    ],
                    'amount' => $booking->amount,
                    'type' => 'seva',
                    'id' => $booking->id,
                ];
                break;

            case 'stay':
                $booking = StayBooking::with('room.hotel')->findOrFail($id);
                $summary = [
                    'title' => 'Accommodation Booking Summary',
                    'details' => [
                        'Hotel' => $booking->room->hotel->name,
                        'Room Type' => $booking->room->type,
                        'Check-in' => Carbon::parse($booking->check_in_date)->format('d M, Y'),
                        'Check-out' => Carbon::parse($booking->check_out_date)->format('d M, Y'),
                        'Guests' => $booking->number_of_guests,
                    ],
                    'amount' => $booking->total_amount,
                    'type' => 'stay',
                    'id' => $booking->id,
                ];
                break;

            case 'ebook':
                $ebook = Ebook::findOrFail($id);
                $summary = [
                    'title' => 'Ebook Purchase Summary',
                    'details' => [
                        'Title' => $ebook->title,
                        'Author' => $ebook->author,
                    ],
                    'amount' => $ebook->price,
                    'type' => 'ebook',
                    'id' => $ebook->id,
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

        $order = null;
        $amount = 0;

        switch ($validated['order_type']) {
            case 'darshan':
                $order = DarshanBooking::findOrFail($validated['order_id']);
                $amount = 50.00 * $order->number_of_people;
                $order->status = 'Confirmed';
                $redirectRoute = 'profile.bookings';
                $successMessage = 'Your Darshan booking is confirmed!';
                break;
            case 'seva':
                $order = Sevabooking::findOrFail($validated['order_id']);
                $amount = $order->amount;
                $order->status = 'Confirmed';
                $redirectRoute = 'profile.bookings';
                $successMessage = 'Your Seva booking is confirmed!';
                break;
            case 'stay':
                $order = StayBooking::findOrFail($validated['order_id']);
                $amount = $order->total_amount;
                $order->status = 'Confirmed';
                $redirectRoute = 'profile.bookings';
                $successMessage = 'Your accommodation is confirmed!';
                break;
            case 'ebook':
                $order = Ebook::findOrFail($validated['order_id']);
                $amount = $order->price;
                Auth::user()->ebooks()->attach($order->id);
                $redirectRoute = 'profile.ebooks';
                $successMessage = 'eBook purchased successfully!';
                break;
        }

        if ($order) {
            $order->save();

            // Create a record in the payments table
            Payment::create([
                'user_id' => Auth::id(),
                'payment_id' => $validated['payment_id'],
                'entity_type' => get_class($order),
                'entity_id' => $order->id,
                'amount' => $amount,
                'currency' => 'INR',
                'status' => 'Completed',
            ]);

            return redirect()->route($redirectRoute)->with('success', $successMessage);
        }

        return redirect()->route('home')->with('error', 'Could not confirm your payment.');
    }
}
