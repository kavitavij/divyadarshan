<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RefundRequest;
use App\Models\StayBooking;
use Illuminate\Http\Request;

class BookingCancelController extends Controller
{
    /**
     * Display a listing of all refund requests for accommodations.
     */
    public function index()
{
    $refundRequests = RefundRequest::where('booking_type', StayBooking::class)
        ->whereHas('bookingable') // <-- ADD THIS LINE
        ->with('bookingable.user', 'bookingable.room.hotel')
        ->latest()
        ->paginate(15);

    return view('admin.booking-cancel.index', compact('refundRequests'));
}
    /**
     * Display the details for a specific stay refund request.
     */
    public function showStayRefund(RefundRequest $refundRequest)
    {
        // Safety check to ensure we're viewing the correct type
        if ($refundRequest->booking_type !== StayBooking::class) {
            abort(404);
        }

        $refundRequest->load('bookingable.user', 'bookingable.room.hotel', 'bookingable.guests');

        return view('admin.booking-cancel.show-stay', compact('refundRequest'));
    }

    /**
     * Update the status of a given refund request.
     */
    public function updateRefundStatus(Request $request, RefundRequest $refundRequest)
{
    $validatedData = $request->validate([
        'status' => 'required|string|in:Pending,Successful,Failed'
    ]);

    // 1. Update the status on the refund request itself
    $refundRequest->update([
        'status' => $validatedData['status']
    ]);

    // 2. Find the parent booking (the StayBooking) and update it explicitly
    if ($refundRequest->bookingable) {
        $booking = $refundRequest->bookingable; // Get the related StayBooking model
        $booking->update([
            'refund_status' => $validatedData['status']
        ]);
    }

    return redirect()->route('admin.booking-cancel.index')
                     ->with('success', 'Refund status has been updated successfully.');
}
}
