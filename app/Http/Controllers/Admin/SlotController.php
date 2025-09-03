<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DarshanSlot;
use App\Models\Temple;
use Illuminate\Http\Request;

class SlotController extends Controller
{
    public function index(Request $request)
    {
        $filterTemple = $request->input('temple_id');
        $filterDate = $request->input('date');

        // Start building the query
        $slotsQuery = DarshanSlot::with('temple')
            ->orderBy('slot_date', 'desc')
            ->orderBy('start_time', 'asc');

        // Apply filters if they exist
        if ($filterTemple) {
            $slotsQuery->where('temple_id', $filterTemple);
        }
        if ($filterDate) {
            $slotsQuery->where('slot_date', $filterDate);
        }

        $slots = $slotsQuery->paginate(20);
        $temples = Temple::orderBy('name')->get(); // For the filter dropdown

        return view('admin.slots.index', compact('slots', 'temples', 'filterTemple', 'filterDate'));
    }

    public function create()
    {
        $temples = Temple::orderBy('name')->get();
        return view('admin.slots.create', compact('temples'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'temple_id' => 'required|exists:temples,id',
            'slot_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'total_capacity' => 'required|integer|min:1',
        ]);

        DarshanSlot::create([
            'temple_id' => $request->temple_id,
            'slot_date' => $request->slot_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'total_capacity' => $request->total_capacity,
            'booked_capacity' => 0,
        ]);

        return redirect()->route('admin.slots.index')->with('success', 'Darshan slot created successfully.');
    }

    public function edit(DarshanSlot $slot)
    {
        $temples = Temple::orderBy('name')->get();
        return view('admin.slots.edit', compact('slot', 'temples'));
    }

    public function update(Request $request, DarshanSlot $slot)
    {
        $request->validate([
            'temple_id' => 'required|exists:temples,id',
            'slot_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'total_capacity' => 'required|integer|min:' . $slot->booked_capacity,
        ]);

        $slot->update($request->all());

        return redirect()->route('admin.slots.index')->with('success', 'Slot updated successfully.');
    }

    public function destroy(DarshanSlot $slot)
    {
        if ($slot->booked_capacity > 0) {
            return redirect()->back()->with('error', 'Cannot delete a slot that has active bookings.');
        }

        $slot->delete();
        return redirect()->route('admin.slots.index')->with('success', 'Slot deleted successfully.');
    }
}
