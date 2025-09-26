<?php

namespace App\Http\Controllers\HotelManager;

use App\Http\Controllers\Controller;
use App\Models\StayBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HotelManagerBookingController extends Controller
{
    /**
     * Cancel a booking and initiate the refund request process.
     */
    public function cancel(StayBooking $booking)
{
    // 1. Authorize: Ensure the booking belongs to the manager's hotel
    $managerHotelId = Auth::user()->hotel->id ?? null;
    if ($booking->hotel_id !== $managerHotelId) {
        abort(403, 'Unauthorized action.');
    }

    // 2. Update Statuses
    $booking->status = 'Cancelled';
    $booking->refund_status = 'pending';
    $booking->save();

    // 3. (NEW) Notify the manager about the cancellation
    $manager = Auth::user(); // The manager is the one performing the action
    $manager->notify(new \App\Notifications\BookingCancelled($booking));

    // 4. Redirect with success message
    return redirect()->route('hotel-manager.guest-list.index')
        ->with('success', 'Booking #' . $booking->id . ' has been cancelled and the refund request has been initiated.');
}
}
