<?php

namespace App\Http\Controllers\HotelManager;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Temple;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HotelController extends Controller
{
    /**
     * Show the form for editing the hotel managed by the current user.
     */
    public function edit()
    {
        $manager = Auth::user();
        $hotel = $manager->hotel;

        if (!$hotel) {
            return redirect()->route('hotel-manager.dashboard')->with('error', 'You are not assigned to a hotel.');
        }

        $temples = Temple::orderBy('name')->get();
        return view('hotel-manager.hotel.edit', compact('hotel', 'temples'));
    }

    /**
     * Update the hotel's information in storage.
     */
    public function update(Request $request)
    {
        $manager = Auth::user();
        $hotel = $manager->hotel;

        if (!$hotel) {
            return redirect()->route('hotel-manager.dashboard')->with('error', 'You are not assigned to a hotel.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'temple_id' => 'nullable|exists:temples,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            if ($hotel->image && file_exists(public_path($hotel->image))) {
                unlink(public_path($hotel->image));
            }
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images/hotels'), $imageName);
            $data['image'] = 'images/hotels/' . $imageName;
        }

        $hotel->update($data);

        return redirect()->route('hotel-manager.dashboard')->with('success', 'Hotel details updated successfully.');
    }
}
