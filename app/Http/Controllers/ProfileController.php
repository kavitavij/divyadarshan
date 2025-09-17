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
use App\Models\DarshanSlot;

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

        // THIS IS THE FIX: Eager load the new 'devotees' and 'temple' relationships
        $booking->load('temple', 'devotees');

        // Load the PDF view from the correct path
        $pdf = PDF::loadView('booking.receipt-pdf', compact('booking'));

        // Generate a unique filename and stream the PDF to the browser
        $fileName = 'Darshan-Booking-Receipt-' . str_pad($booking->id, 6, '0', STR_PAD_LEFT) . '.pdf';
        return $pdf->stream($fileName);
    }
    public function cancelBooking(Booking $booking): RedirectResponse
    {
        if (Auth::id() !== $booking->user_id) {
            abort(403, 'Unauthorized action.');
        }

        DB::transaction(function () use ($booking) {
            if ($booking->darshan_slot_id) {
                $slot = DarshanSlot::find($booking->darshan_slot_id);
                if ($slot) {
                    $slot->booked_capacity = max(0, $slot->booked_capacity - $booking->number_of_people);
                    $slot->save();
                }
            }

            $booking->status = 'Cancelled';
            $booking->save();
        });

        return Redirect::route('profile.my-bookings.index')
                    ->with('success', 'Booking cancelled successfully.');
    }
    public function requestRefund(Booking $booking): View
    {
        // Security Check
        if (Auth::id() !== $booking->user_id) {
            abort(403);
        }

        return view('profile.my-bookings.request-refund', compact('booking'));
    }
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
        $booking->update(['refund_status' => 'Pending']);

        return redirect()->route('profile.my-stays.index')->with('success', 'Refund request submitted successfully!');
    }
    public function myStays(Request $request)
    {
        $query = StayBooking::query()
            ->where('user_id', auth()->id())
            ->with('room.hotel', 'user');

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('q')) {
            $searchTerm = $request->input('q');
            // This searches through the related hotel's name
            $query->whereHas('room.hotel', function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%');
            });
        }

        $bookings = $query->orderBy('check_in_date', 'desc')
                        ->paginate(10);

        $bookings->appends($request->query());

        return view('profile.my-stays.index', compact('bookings'));
    }
    public function requestStayRefund(StayBooking $booking): View
    {
        if (auth()->id() !== $booking->user_id) {
            abort(403);
        }
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

