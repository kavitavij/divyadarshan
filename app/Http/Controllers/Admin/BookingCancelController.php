<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingCancelController extends Controller
{
    public function index()
{
    $bookings = Booking::where('status', 'cancelled')->paginate(10);
    return view('admin.booking-cancel.index', compact('bookings'));
}

   public function show($id)
{
    $booking = Booking::with(['user', 'temple', 'hotel', 'refundRequest'])->findOrFail($id);

    return view('admin.booking-cancel.show', compact('booking'));
}
    public function updateRefundStatus(Request $request, $bookingId)
{
    $booking = Booking::with('refundRequest')->findOrFail($bookingId);

    if (!$booking->refundRequest) {
        return redirect()->back()->with('error', 'No refund request found for this booking.');
    }

    // Update the status to Successful
    $booking->refundRequest->update([
        'status' => 'Successful',
    ]);

    // Optionally, also mark the booking refund_status column
    $booking->update([
        'refund_status' => 'refunded',
    ]);

    return redirect()->back()->with('success', 'Refund status updated to Successful.');
}
}
