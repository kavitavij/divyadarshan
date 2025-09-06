<?php

namespace App\Http\Controllers\HotelManager;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
        $rooms = $hotel->rooms()->with('photos')->latest()->paginate(10);
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
        if (!$hotel) {
            return redirect()->route('hotel-manager.dashboard')->with('error', 'You are not assigned to a hotel.');
        }

        $request->validate([
            'type' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'price_per_night' => 'required|numeric|min:0',
            'total_rooms' => 'required|integer|min:1',
            'room_size' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'facilities' => 'nullable|array',
            'facilities.*' => 'string',
            'photos' => 'required|array|min:1',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        DB::beginTransaction();
        try {
            $roomData = $request->only(['type', 'capacity', 'price_per_night', 'total_rooms', 'room_size', 'description']);
            $roomData['facilities'] = json_encode($request->input('facilities', []));
            $room = $hotel->rooms()->create($roomData);

            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $path = $photo->store('rooms', 'public');
                    $room->photos()->create(['path' => $path]);
                }
            }

            DB::commit();
            return redirect()->route('hotel-manager.rooms.index')->with('success', 'Room added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating room: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while adding the room. Please try again.')->withInput();
        }
    }

    public function edit(Room $room)
    {
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
            'room_size' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'facilities' => 'nullable|array',
            'facilities.*' => 'string',
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        DB::beginTransaction();
        try {
            $roomData = $request->only(['type', 'capacity', 'price_per_night', 'total_rooms', 'room_size', 'description']);
            $roomData['facilities'] = json_encode($request->input('facilities', []));
            $room->update($roomData);

            // *** THIS IS THE NEW LOGIC TO HANDLE NEW PHOTO UPLOADS ***
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $path = $photo->store('rooms', 'public');
                    $room->photos()->create(['path' => $path]);
                }
            }

            DB::commit();
            return redirect()->route('hotel-manager.rooms.index')->with('success', 'Room updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating room: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while updating the room. Please try again.')->withInput();
        }
    }

    public function destroy(Room $room)
    {
        if ($room->hotel_id !== $this->getManagerHotel()->id) {
            abort(403);
        }

        DB::beginTransaction();
        try {
            foreach ($room->photos as $photo) {
                Storage::disk('public')->delete($photo->path);
            }
            $room->delete();
            DB::commit();
            return redirect()->route('hotel-manager.rooms.index')->with('success', 'Room and associated photos deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting room: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while deleting the room. Please try again.');
        }
    }

    public function deletePhoto(RoomPhoto $photo)
    {
        $room = $photo->room;
        if ($room->hotel_id !== $this->getManagerHotel()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            Storage::disk('public')->delete($photo->path);
            $photo->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error deleting photo: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Server error, could not delete photo.']);
        }
    }
}

