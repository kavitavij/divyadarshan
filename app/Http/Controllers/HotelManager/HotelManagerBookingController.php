<?php

namespace App\Http\Controllers\HotelManager;

use App\Http\Controllers\Controller;
use App\Models\StayBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\BookingCancelledByManager;

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

        // 2. Update Statuses: This is the core logic
        $booking->status = 'Cancelled';
        $booking->refund_status = 'pending'; 
        $booking->save();

        try {
            // Eager load the user who made the booking
            $booking->load('user');
            if ($booking->user) {
                $booking->user->notify(new BookingCancelledByManager($booking));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error(
                "Failed to send user cancellation notification for booking ID {$booking->id}: " . $e->getMessage()
            );
        }
        return redirect()->route('hotel-manager.guest-list.index')
               ->with('success', 'Booking #' . $booking->id . ' has been cancelled and the refund request has been initiated.');
    }
}
