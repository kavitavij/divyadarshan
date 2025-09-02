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

        // **MODIFIED:** Redirect to the refund request form instead of the bookings list
        return Redirect::route('profile.my-bookings.refund.request', ['booking' => $booking])
                       ->with('status', 'Booking cancelled. Please fill out the form to request your refund.');
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
    public function storeRefundRequest(Request $request, Booking $booking): RedirectResponse
    {
        // Security Check
        if (Auth::id() !== $booking->user_id) {
            abort(403);
        }

        $request->validate([
            'account_holder_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:20',
            'ifsc_code' => 'required|string|max:15',
            'bank_name' => 'required|string|max:255',
        ]);

        RefundRequest::create([
            'booking_id' => $booking->id,
            'user_id' => Auth::id(),
            'account_holder_name' => $request->account_holder_name,
            'account_number' => $request->account_number,
            'ifsc_code' => $request->ifsc_code,
            'bank_name' => $request->bank_name,
        ]);

        return Redirect::route('profile.my-bookings.index')->with('status', 'Refund request submitted successfully. It will be processed within 5-7 business days.');
    }

}

