<?php

namespace App\Http\Controllers\HotelManager;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Temple;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Amenity;
class HotelController extends Controller
{
    /**
     * Show the form for editing the hotel managed by the current user.
     */
   public function edit()
{
    $hotel = Auth::user()->hotel;
    if (!$hotel) {
        return redirect()->route('hotel-manager.dashboard')->with('error', 'You are not assigned to a hotel.');
    }

    // This is the data for the dynamic amenity checkboxes
    $amenities = Amenity::orderBy('name')->get();
    $hotelAmenities = $hotel->amenities->pluck('id')->toArray();

    // THE FIX IS HERE: You were missing this line to fetch the temples
    $temples = \App\Models\Temple::orderBy('name')->get();

    return view('hotel-manager.hotel.edit', compact('hotel', 'amenities', 'hotelAmenities', 'temples'));
}

    /**
     * Update the hotel's information in storage.
     */
    public function update(Request $request)
{
    $hotel = Auth::user()->hotel;
    if (!$hotel) {
        return redirect()->route('hotel-manager.dashboard')
            ->with('error', 'You are not assigned to a hotel.');
    }

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'location' => 'required|string|max:255',
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'rating' => 'nullable|numeric|min:1|max:5',
        'policies' => 'nullable|string',
        'nearby_attractions' => 'nullable|string',
        'amenities' => 'nullable|array',
        'amenities.*' => 'exists:amenities,id',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
    ]);

    // Include all the fields you want to update
    $updateData = $request->only(['name', 'location', 'description', 'rating', 'latitude', 'longitude']);

    // Handle File Upload
    if ($request->hasFile('image')) {
        if ($hotel->image) {
            Storage::disk('public')->delete($hotel->image);
        }
        $path = $request->file('image')->store('hotel_images', 'public');
        $updateData['image'] = $path;
    }

    // Handle Textarea fields
    $updateData['policies'] = $request->filled('policies')
        ? array_filter(array_map('trim', explode("\n", $request->policies)))
        : [];
    $updateData['nearby_attractions'] = $request->filled('nearby_attractions')
        ? array_filter(array_map('trim', explode("\n", $request->nearby_attractions)))
        : [];

    // Update the main hotel details
    $hotel->update($updateData);

    // Sync the amenities relationship
    $hotel->amenities()->sync($request->input('amenities', []));

    return redirect()->route('hotel-manager.hotel.edit')
        ->with('success', 'Hotel details updated successfully!');
}
}
