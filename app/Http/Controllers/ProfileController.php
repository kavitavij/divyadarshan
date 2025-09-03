<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Donation;
use App\Models\Booking;
use App\Models\StayBooking;
use App\Models\RefundRequest;
use Barryvdh\DomPDF\Facade\Pdf;
class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
        public function myEbooks()
        {
            $user = Auth::user();
            // Eager load the ebooks to get all their details
            $ebooks = $user->ebooks()->get();

            return view('profile.my-ebooks', compact('ebooks'));
        }
        // In app/Http/Controllers/ProfileController.php

// Make sure this is at the top of your file

    public function myBookings()
    {
        // This is the cleanest way to get the user's bookings.
        // It fetches the bookings and their related temple data directly.
        $bookings = Booking::query()
            ->where('user_id', auth()->id())
            ->with('temple', 'darshanSlot', 'defaultDarshanSlot')
            ->with('temple')
            ->latest('created_at') // Be explicit about ordering by creation date
            ->paginate(9);

        return view('profile.my-bookings.index', [
            'bookings' => $bookings
        ]);
    }

    public function myDonations(): View
    {
        $donations = Donation::where('user_id', Auth::id())
                             ->with('temple') // Eager load temple details to prevent extra queries
                             ->latest()       // Order by the most recent donation
                             ->paginate(10);   // Paginate the results

        return view('profile.my-donations', compact('donations'));
    }
    public function downloadDonationReceipt(Donation $donation)
    {
        // Security check: ensure the authenticated user owns this donation
        if (Auth::id() !== $donation->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Load the PDF view and pass the donation data to it
        $pdf = PDF::loadView('donations.receipt-pdf', compact('donation'));

        // Generate a unique filename and stream the PDF to the browser for download
        $fileName = 'Donation-Receipt-' . str_pad($donation->id, 6, '0', STR_PAD_LEFT) . '.pdf';
        return $pdf->stream($fileName);
    }
    public function downloadBookingReceipt(Booking $booking)
    {
        // Security check: ensure the authenticated user owns this booking
        if (Auth::id() !== $booking->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Load the PDF view from the correct path: 'bookings.receipt-pdf'
        $pdf = PDF::loadView('booking.receipt-pdf', compact('booking'));

        // Generate a unique filename and stream the PDF to the browser for download
        $fileName = 'Darshan-Booking-Receipt-' . str_pad($booking->id, 6, '0', STR_PAD_LEFT) . '.pdf';
        return $pdf->stream($fileName);
    }
    public function cancelBooking(Booking $booking): RedirectResponse
    {
        // Security Check
        if (Auth::id() !== $booking->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Update booking status
        $booking->status = 'Cancelled';
        $booking->save();

        //  Redirect to the refund request form instead of the bookings list
        // return Redirect::route('profile.my-bookings.refund.request', ['booking' => $booking])
        //                ->with('status', 'Booking cancelled. Please fill out the form to request your refund.');
    // This new line will redirect back to the bookings list with a simple success message
    return Redirect::route('profile.my-bookings.index')
                ->with('success', 'Booking cancelled successfully.');
    }

    /**
     * Show the refund request form.
     * ADD THIS NEW FUNCTION
     */
    public function requestRefund(Booking $booking): View
    {
        // Security Check
        if (Auth::id() !== $booking->user_id) {
            abort(403);
        }

        return view('profile.my-bookings.request-refund', compact('booking'));
    }

    /**
     * Store the refund request details.
     * ADD THIS NEW FUNCTION
     */
    // In app/Http/Controllers/ProfileController.php

public function storeStayRefundRequest(Request $request, StayBooking $booking): RedirectResponse
{
    if (auth()->id() !== $booking->user_id) {
        abort(403);
    }

    $validatedData = $request->validate([
        'account_holder_name' => 'required|string|max:255',
        'account_number' => 'required|string|max:20',
        'ifsc_code' => 'required|string|max:15',
        'bank_name' => 'required|string|max:255',
    ]);

    // Use the relationship to create the refund request
    $booking->refundRequests()->create([
        'user_id' => auth()->id(),
        'account_holder_name' => $validatedData['account_holder_name'],
        'account_number' => $validatedData['account_number'],
        'ifsc_code' => $validatedData['ifsc_code'],
        'bank_name' => $validatedData['bank_name'],
        'status' => 'Pending',
    ]);

    // ** THIS IS THE NEW LINE **
    // Update the refund_status on the booking itself
    $booking->update(['refund_status' => 'Pending']);

    return redirect()->route('profile.my-stays.index')->with('success', 'Refund request submitted successfully!');
}
    public function myStays()
    {
        $bookings = StayBooking::with('room.hotel', 'user') // Eager load relationships
                                ->where('user_id', auth()->id())
                                ->orderBy('check_in_date', 'desc')
                                ->paginate(10); // Or your preferred number

        return view('profile.my-stays.index', compact('bookings'));
    }
    public function cancelStayBooking(StayBooking $booking)
    {
        if (auth()->id() !== $booking->user_id) {
            abort(403);
        }
        if (now()->startOfDay()->gt($booking->check_in_date)) {
            return redirect()->back()->with('error', 'You can only cancel bookings with a future check-in date.');
        }

        $booking->status = 'Cancelled';
        $booking->save();

        // ** THIS IS THE CHANGE **
        // Redirect to the refund request form instead of the bookings list
        return redirect()->route('profile.my-stays.refund.request', $booking)
                         ->with('success', 'Booking cancelled. Please provide your bank details to request a refund.');
    }
    public function requestStayRefund(StayBooking $booking): View
    {
        if (auth()->id() !== $booking->user_id) {
            abort(403);
        }
        // We can reuse the same view as the darshan refund request if the fields are the same
        return view('profile.my-stays.request-refund', compact('booking'));
    }

    /**
     * Store the refund request details for a StayBooking.
     * NEW METHOD
     */
//     public function storeStayRefundRequest(Request $request, StayBooking $booking): RedirectResponse
// {
//     if (auth()->id() !== $booking->user_id) {
//         abort(403);
//     }

//     $validatedData = $request->validate([
//         'account_holder_name' => 'required|string|max:255',
//         'account_number' => 'required|string|max:20',
//         'ifsc_code' => 'required|string|max:15',
//         'bank_name' => 'required|string|max:255',
//     ]);

//     // Use the new relationship to create the refund request
//     $booking->refundRequests()->create([
//         'user_id' => auth()->id(),
//         'account_holder_name' => $validatedData['account_holder_name'],
//         'account_number' => $validatedData['account_number'],
//         'ifsc_code' => $validatedData['ifsc_code'],
//         'bank_name' => $validatedData['bank_name'],
//         'status' => 'Pending',
//     ]);

//     return redirect()->route('profile.my-stays.index')->with('success', 'Refund request submitted successfully!');
// }

    /**
     * Generates and downloads a PDF receipt for a stay booking.
     * NEW METHOD
     */
   public function downloadStayReceipt(StayBooking $booking)
{
    // Authorize...
    if (auth()->id() !== $booking->user_id) {
        abort(403);
    }

    // THIS IS THE IMPORTANT PART: Make sure 'room.hotel' is loaded.
    $booking->load('room.hotel', 'user');

    $pdf = Pdf::loadView('receipts.stay', ['booking' => $booking]);
    return $pdf->download('receipt-stay-' . $booking->id . '.pdf');
}

}

