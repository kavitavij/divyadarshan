<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Temple;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    /**
     * Display a listing of the hotels.
     */
    public function index()
    {
        $hotels = Hotel::with('temple')->latest()->paginate(10);
        return view('admin.hotels.index', compact('hotels'));
    }

    /**
     * Show the form for creating a new hotel.
     */
    public function create()
    {
        $temples = Temple::orderBy('name')->get();
        return view('admin.hotels.create', compact('temples'));
    }

    /**
     * Store a newly created hotel in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'temple_id' => 'nullable|exists:temples,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images/hotels'), $imageName);
            $data['image'] = 'images/hotels/' . $imageName;
        }

        Hotel::create($data);

        return redirect()->route('admin.hotels.index')->with('success', 'Hotel created successfully.');
    }

    /**
     * NEW: Show the form for editing the specified hotel.
     */
    public function edit(Hotel $hotel)
    {
        $temples = Temple::orderBy('name')->get();
        return view('admin.hotels.edit', compact('hotel', 'temples'));
    }

    /**
     * NEW: Update the specified hotel in storage.
     */
    public function update(Request $request, Hotel $hotel)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'temple_id' => 'nullable|exists:temples,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            // Optional: Delete old image
            if ($hotel->image && file_exists(public_path($hotel->image))) {
                unlink(public_path($hotel->image));
            }
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images/hotels'), $imageName);
            $data['image'] = 'images/hotels/' . $imageName;
        }

        $hotel->update($data);

        return redirect()->route('admin.hotels.index')->with('success', 'Hotel updated successfully.');
    }

    // You can add a destroy method here later
}
