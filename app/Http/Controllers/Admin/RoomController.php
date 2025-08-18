<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the rooms for a specific hotel.
     */
    public function index(Hotel $hotel)
    {
        $rooms = $hotel->rooms()->latest()->paginate(10);
        return view('admin.rooms.index', compact('hotel', 'rooms'));
    }

    /**
     * Show the form for creating a new room for a specific hotel.
     */
    public function create(Hotel $hotel)
    {
        return view('admin.rooms.create', compact('hotel'));
    }

    /**
     * Store a newly created room in storage.
     */
    public function store(Request $request, Hotel $hotel)
    {
        $request->validate([
            'type' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'price_per_night' => 'required|numeric|min:0',
            'total_rooms' => 'required|integer|min:1',
        ]);

        $hotel->rooms()->create($request->all());

        return redirect()->route('admin.hotels.rooms.index', $hotel)->with('success', 'Room added successfully.');
    }

    /**
     * NEW: Show the form for editing the specified room.
     */
    public function edit(Room $room)
    {
        // The hotel is loaded via the room's relationship
        return view('admin.rooms.edit', compact('room'));
    }

    /**
     * NEW: Update the specified room in storage.
     */
    public function update(Request $request, Room $room)
    {
        $request->validate([
            'type' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'price_per_night' => 'required|numeric|min:0',
            'total_rooms' => 'required|integer|min:1',
        ]);

        $room->update($request->all());

        return redirect()->route('admin.hotels.rooms.index', $room->hotel_id)->with('success', 'Room updated successfully.');
    }

    // You can add a destroy method here later.
}
