<?php

namespace App\Http\Controllers\TempleManager;

use App\Http\Controllers\Controller;
use App\Models\DarshanSlot;
use App\Models\Temple;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DarshanSlotController extends Controller
{
    private function getManagerTemple()
    {
        // Helper function to get the manager's assigned temple
        return Auth::user()->temple;
    }

    public function index(Request $request)
    {
        $temple = $this->getManagerTemple();
        if (!$temple) {
            return redirect()->route('temple-manager.dashboard')->with('error', 'You are not assigned to a temple.');
        }

        $selectedDate = $request->input('date', Carbon::today()->toDateString());

        $slots = $temple->darshanSlots()
                       ->where('slot_date', $selectedDate)
                       ->orderBy('start_time')
                       ->get();

        return view('temple-manager.slots.index', compact('temple', 'slots', 'selectedDate'));
    }

    public function store(Request $request)
    {
        $temple = $this->getManagerTemple();
        $request->validate([
            'slot_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'total_capacity' => 'required|integer|min:1',
        ]);

        $temple->darshanSlots()->create($request->all());

        return redirect()->route('temple-manager.slots.index', ['date' => $request->slot_date])
                         ->with('success', 'Slot created successfully!');
    }

    public function edit(DarshanSlot $slot)
    {
        // Security Check: Ensure the slot belongs to the manager's temple
        if ($slot->temple_id !== $this->getManagerTemple()->id) {
            abort(403);
        }
        return view('temple-manager.slots.edit', compact('slot'));
    }

    public function update(Request $request, DarshanSlot $slot)
    {
        if ($slot->temple_id !== $this->getManagerTemple()->id) {
            abort(403);
        }
        $request->validate([
            'slot_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'total_capacity' => 'required|integer|min:' . $slot->booked_capacity,
        ]);

        $slot->update($request->all());

        return redirect()->route('temple-manager.slots.index', ['date' => $slot->slot_date->format('Y-m-d')])
                         ->with('success', 'Slot updated successfully!');
    }

    public function destroy(DarshanSlot $slot)
    {
        if ($slot->temple_id !== $this->getManagerTemple()->id) {
            abort(403);
        }
        if ($slot->booked_capacity > 0) {
            return redirect()->back()->with('error', 'Cannot delete a slot that has active bookings.');
        }
        $slotDate = $slot->slot_date->format('Y-m-d');
        $slot->delete();

        return redirect()->route('temple-manager.slots.index', ['date' => $slotDate])
                         ->with('success', 'Slot deleted successfully!');
    }
}
