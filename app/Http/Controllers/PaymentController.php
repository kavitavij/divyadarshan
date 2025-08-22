<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Donation;
use App\Models\Ebook;
use App\Models\SevaBooking;
use App\Models\AccommodationBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Razorpay\Api\Api;

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
        $amount = 0;

        switch ($type) {
            case 'darshan':
                $booking = Booking::with('temple')->findOrFail($id);
                $amount = $booking->number_of_people * 100; // Example price
                $summary = [
                    'title' => 'Darshan Booking Summary',
                    'details' => [
                        'Temple' => $booking->temple->name,
                        'Number of Devotees' => $booking->number_of_people,
                    ],
                    'amount' => $amount,
                    'confirm_route' => route('booking.confirm'),
                    'booking_id' => $booking->id,
                ];
                break;

            case 'seva':
                $sevaBooking = SevaBooking::with('seva.temple')->findOrFail($id);
                $amount = $sevaBooking->amount;
                $summary = [
                    'title' => 'Seva Booking Summary',
                    'details' => [
                        'Temple' => $sevaBooking->seva->temple->name,
                        'Seva' => $sevaBooking->seva->name,
                    ],
                    'amount' => $amount,
                    'confirm_route' => route('sevas.booking.confirm'),
                    'booking_id' => $sevaBooking->id,
                ];
                break;

            case 'donation':
                $donation = Donation::findOrFail($id);
                $amount = $donation->amount;
                $summary = [
                    'title' => 'Donation Summary',
                    'details' => [
                        'Donation Type' => 'General Temple Fund',
                    ],
                    'amount' => $amount,
                    'confirm_route' => route('donations.confirm'),
                    'donation_id' => $donation->id,
                ];
                break;

            case 'ebook':
                $ebook = Ebook::findOrFail($id);
                $amount = $ebook->price;
                $summary = [
                    'title' => 'eBook Purchase Summary',
                    'details' => [
                        'Title' => $ebook->title,
                        'Author' => $ebook->author,
                    ],
                    'amount' => $amount,
                    'confirm_route' => route('ebooks.confirmPurchase'),
                    'ebook_id' => $ebook->id,
                ];
                break;

            case 'accommodation':
                $booking = AccommodationBooking::with(['room.hotel'])->findOrFail($id);
                $amount = $booking->total_amount;
                $summary = [
                    'title' => 'Accommodation Booking Summary',
                    'details' => [
                        'Hotel' => $booking->room->hotel->name,
                        'Room Type' => $booking->room->type,
                        'Check-in' => \Carbon\Carbon::parse($booking->check_in_date)->format('d M Y'),
                        'Check-out' => \Carbon\Carbon::parse($booking->check_out_date)->format('d M Y'),
                        'Guests' => $booking->number_of_guests,
                    ],
                    'amount' => $amount,
                    'confirm_route' => route('stays.confirm'),
                    'booking_id' => $booking->id,
                ];
                break;
        }

        if ($amount <= 0) {
            return back()->with('error', 'The transaction amount must be greater than zero. Please check the price of the item.');
        }

        // 🔹 Create Razorpay order
        $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
        $order = $api->order->create([
            'receipt'  => strtoupper($type) . '_' . $id . '_' . time(),
            'amount'   => $amount * 100, // convert to paise
            'currency' => 'INR'
        ]);

        $orderId = $order['id'];

        return view('shared.payment', compact('summary', 'orderId'));
    }

    /**
     * Handle Razorpay callback and verify payment.
     */
    public function callback(Request $request)
    {
        // ... Your callback logic ...
    }
}
