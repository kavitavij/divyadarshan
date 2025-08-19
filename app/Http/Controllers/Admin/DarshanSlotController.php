<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DarshanSlot;
use App\Models\Temple;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DarshanSlotController extends Controller
{
    /**
     * Display the slot management page.
     * Shows a date picker and lists slots for the selected date.
     */
    public function index(Temple $temple, Request $request)
    {
        // Default to today's date if no date is selected
        $selectedDate = $request->input('date', Carbon::today()->toDateString());
        
        // Fetch slots only for the selected date
        $slots = $temple->darshanSlots()
                       ->where('slot_date', $selectedDate)
                       ->orderBy('start_time')
                       ->get();
        
        return view('admin.slots.index', compact('temple', 'slots', 'selectedDate'));
    }

    /**
     * Store a new darshan slot in the database for a specific date.
     */
    public function store(Request $request, Temple $temple)
    {
        $request->validate([
            'slot_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'total_capacity' => 'required|integer|min:1',
        ]);

        $temple->darshanSlots()->create($request->all());

        // Redirect back to the same date view to see the new slot
        return redirect()->route('admin.temples.slots.index', ['temple' => $temple, 'date' => $request->slot_date])
                         ->with('success', 'Slot created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DarshanSlot $slot)
    {
        return view('admin.slots.edit', compact('slot'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DarshanSlot $slot)
    {
        $request->validate([
            'slot_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'total_capacity' => 'required|integer|min:1',
        ]);

        $slot->update($request->all());

        return redirect()->route('admin.temples.slots.index', ['temple' => $slot->temple_id, 'date' => $slot->slot_date->format('Y-m-d')])
                         ->with('success', 'Slot updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DarshanSlot $slot)
    {
        $templeId = $slot->temple_id;
        $slotDate = $slot->slot_date->format('Y-m-d');
        $slot->delete();

        return redirect()->route('admin.temples.slots.index', ['temple' => $templeId, 'date' => $slotDate])
                         ->with('success', 'Slot deleted successfully!');
    }
}
