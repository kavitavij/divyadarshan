<?php

namespace App\Http\Controllers\HotelManager;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    private function getManagerHotel()
    {
        return Auth::user()->hotel;
    }

    public function index()
    {
        $hotel = $this->getManagerHotel();
        if (!$hotel) {
            return redirect()->route('hotel-manager.dashboard')->with('error', 'You are not assigned to a hotel.');
        }
        $rooms = $hotel->rooms()->latest()->paginate(10);
        return view('hotel-manager.rooms.index', compact('hotel', 'rooms'));
    }

    public function create()
    {
        $hotel = $this->getManagerHotel();
        return view('hotel-manager.rooms.create', compact('hotel'));
    }

    public function store(Request $request)
    {
        $hotel = $this->getManagerHotel();
        $request->validate([
            'type' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'price_per_night' => 'required|numeric|min:0',
            'total_rooms' => 'required|integer|min:1',
        ]);

        $hotel->rooms()->create($request->all());
        return redirect()->route('hotel-manager.rooms.index')->with('success', 'Room added successfully.');
    }

    public function edit(Room $room)
    {
        // Security check: ensure the room belongs to the manager's hotel
        if ($room->hotel_id !== $this->getManagerHotel()->id) {
            abort(403);
        }
        return view('hotel-manager.rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        if ($room->hotel_id !== $this->getManagerHotel()->id) {
            abort(403);
        }
        $request->validate([
            'type' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'price_per_night' => 'required|numeric|min:0',
            'total_rooms' => 'required|integer|min:1',
        ]);

        $room->update($request->all());
        return redirect()->route('hotel-manager.rooms.index')->with('success', 'Room updated successfully.');
    }

    public function destroy(Room $room)
    {
        if ($room->hotel_id !== $this->getManagerHotel()->id) {
            abort(403);
        }
        $room->delete();
        return redirect()->route('hotel-manager.rooms.index')->with('success', 'Room deleted successfully.');
    }
}
