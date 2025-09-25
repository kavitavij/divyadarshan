<?php

namespace App\Http\Controllers\HotelManager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TermsController extends Controller
{
    /**
     * Show the form for editing the hotel's terms and conditions.
     */
    public function edit()
    {
        $hotel = Auth::user()->hotel;
        return view('hotel-manager.terms.edit', compact('hotel'));
    }

    /**
     * Update the hotel's terms and conditions in the database.
     */
    public function update(Request $request)
    {
        $hotel = Auth::user()->hotel;

        $request->validate([
            'terms_and_conditions' => 'nullable|string',
        ]);

        $hotel->update([
            'terms_and_conditions' => $request->terms_and_conditions,
        ]);

        return redirect()->route('hotel-manager.terms.edit')->with('success', 'Terms and Conditions updated successfully.');
    }
}