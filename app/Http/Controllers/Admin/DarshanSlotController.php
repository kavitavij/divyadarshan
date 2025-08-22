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
     * Show the form for creating/viewing slots for a temple.
     */
    public function index(Temple $temple, Request $request)
    {
        // Define the default slots that will appear on the form
        $defaultSlots = [
            ['start_time' => '09:00', 'end_time' => '11:00'],
            ['start_time' => '11:00', 'end_time' => '13:00'],
            ['start_time' => '15:00', 'end_time' => '17:00'],
        ];

        // Default to today's date if no date is selected
        $selectedDate = $request->input('date', Carbon::today()->toDateString());

        // Fetch slots only for the selected date
        $slots = $temple->darshanSlots()
                       ->where('slot_date', $selectedDate)
                       ->orderBy('start_time')
                       ->get();

        return view('admin.slots.index', compact('temple', 'slots', 'defaultSlots', 'selectedDate'));
    }

    /**
     * Store multiple new darshan slots from the form.
     */
    public function store(Request $request, Temple $temple)
    {
        $request->validate([
            'slot_date' => 'required|date',
            'slots' => 'required|array', // Expecting an array of selected slots
        ]);

        foreach ($request->slots as $index => $slotData) {
            // Only create a slot if the checkbox for it was selected
            if (isset($slotData['create']) && $slotData['create'] == '1') {
                DarshanSlot::create([
                    'temple_id' => $temple->id,
                    'slot_date' => $request->slot_date,
                    'start_time' => $slotData['start_time'],
                    'end_time' => $slotData['end_time'],
                    'total_capacity' => $slotData['total_capacity'],
                ]);
            }
        }

        return redirect()->route('admin.temples.slots.index', ['temple' => $temple, 'date' => $request->slot_date])
                         ->with('success', 'Selected slots created successfully!');
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
