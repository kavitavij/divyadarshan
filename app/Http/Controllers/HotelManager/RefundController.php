<?php

namespace App\Http\Controllers\HotelManager;

use App\Http\Controllers\Controller;
use App\Models\StayBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RefundController extends Controller
{
    public function index()
    {
        $manager = Auth::user();
        $hotel = $manager->hotel;

        if (!$hotel) {
            return redirect()->route('hotel-manager.dashboard')->with('error', 'You are not assigned to a hotel.');
        }

        $refundRequests = StayBooking::where('hotel_id', $hotel->id)
            ->whereRaw('LOWER(refund_status) = ?', ['pending'])
            ->with('user', 'room')
            ->latest()
            ->paginate(15);

        return view('hotel-manager.refund.index', compact('refundRequests', 'hotel'));
    }

    public function show(StayBooking $booking)
    {
        $this->authorizeManagerAccess($booking);

        $booking->load('user', 'room', 'hotel', 'guests');

        $refundRequest = $booking->refundRequests()->latest()->first();

        return view('hotel-manager.refund.show', compact('booking', 'refundRequest'));
    }

    public function updateStatus(Request $request, StayBooking $booking)
    {
        $this->authorizeManagerAccess($booking);

        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $booking->refund_status = $validated['status'];
        $booking->save();
        if ($refundRequest = $booking->refundRequests()->latest()->first()) {
            $refundRequest->status = ucfirst($validated['status']);
            $refundRequest->save();
        }

        return redirect()->route('hotel-manager.refund.index')->with('success', 'Refund status has been updated successfully.');
    }

    private function authorizeManagerAccess(StayBooking $booking)
    {
        $managerHotelId = Auth::user()->hotel->id ?? null;
        if ($booking->hotel_id !== $managerHotelId) {
            abort(403, 'Unauthorized action.');
        }
    }
}
