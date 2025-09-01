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
        public function myBookings(): View
    {
        $user = Auth::user();

        // Get all bookings for the current user, load the temple details,
        // and order them with the newest first.
        $bookings = $user->bookings()->with('temple')->latest()->paginate(10);

       return view('profile.my-bookings.index', compact('bookings'));
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
}

