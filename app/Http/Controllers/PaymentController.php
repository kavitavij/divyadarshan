<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Donation;
use App\Models\Ebook;
use App\Models\SevaBooking;
use App\Models\StayBooking; // THE FIX: Added the missing import for the StayBooking model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Display the universal payment page.
     */
    public function create($id, $type)
    {
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

            case 'ebook':
                $ebook = Ebook::findOrFail($id);
                $summary = [
                    'title' => 'eBook Purchase Summary',
                    'details' => [
                        'Title' => $ebook->title,
                        'Author' => $ebook->author,
                    ],
                    'amount' => $ebook->price,
                    'confirm_route' => route('ebooks.confirmPurchase'),
                    'ebook_id' => $ebook->id,
                ];
                break;

            case 'stay':
                $stayBooking = StayBooking::with(['room.hotel'])->findOrFail($id);
                $summary = [
                    'title' => 'Accommodation Booking Summary',
                    'details' => [
                        'Hotel' => $stayBooking->room->hotel->name,
                        'Room Type' => $stayBooking->room->type,
                        'Check-in' => \Carbon\Carbon::parse($stayBooking->check_in_date)->format('d M Y'),
                        'Check-out' => \Carbon\Carbon::parse($stayBooking->check_out_date)->format('d M Y'),
                        'Guests' => $stayBooking->number_of_guests,
                    ],
                    'amount' => $stayBooking->total_amount,
                    'confirm_route' => route('stays.payment.confirm'), // Assumes this route exists
                    'booking_id' => $stayBooking->id,
                ];
                break;
        }

        return view('shared.payment', compact('summary'));
    }
}
