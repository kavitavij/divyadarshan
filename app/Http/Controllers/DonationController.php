<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Temple; // 1. Import the Temple model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonationController extends Controller
{
    /**
     * Display the main donations page.
     */
    public function index()
    {
        // 2. Fetch all temples to pass to the view
        $temples = Temple::orderBy('name')->get();
        return view('donations.index', compact('temples'));
    }

    /**
     * Store the donation and proceed to payment.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $donation = Donation::create([
            'user_id' => Auth::id(),
            'amount' => $request->amount,
            'temple_id' => $request->temple_id, // 4. Save the temple ID
            'status' => 'Pending Payment',
        ]);

        return redirect()->route('donations.payment', $donation);
    }

    /**
     * Display the payment page.
     */
    public function payment(Donation $donation)
    {
        if ($donation->user_id !== Auth::id()) {
            abort(403);
        }
        // Eager load the temple relationship if it exists
        $donation->load('temple');
        return redirect()->route('payment.create', ['type' => 'donation', 'id' => $donation->id]);
    }

    /**
     * Confirm the donation after payment.
     */
    public function confirm(Request $request)
    {
        $request->validate(['donation_id' => 'required|exists:donations,id']);

        $donation = Donation::findOrFail($request->donation_id);
        $donation->status = 'Completed';
        $donation->save();

        return redirect()->route('home')->with('success', 'Thank you for your generous donation!');
    }
}
