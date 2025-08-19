<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DarshanSlot;
use App\Models\Temple;
use Illuminate\Http\Request;

class DarshanSlotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Temple $temple)
    {
        $slots = $temple->darshanSlots()->orderBy('slot_date', 'desc')->paginate(15);
        return view('admin.slots.index', compact('temple', 'slots'));
    }

    /**
     * Store a newly created resource in storage.
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

        return back()->with('success', 'Darshan slot created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DarshanSlot $slot)
    {
        // Pass the slot to the edit view
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

        // Redirect back to the index page for that temple
        return redirect()->route('admin.temples.slots.index', $slot->temple_id)->with('success', 'Darshan slot updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DarshanSlot $slot)
    {
        $templeId = $slot->temple_id; // Get the temple ID before deleting
        $slot->delete();

        return redirect()->route('admin.temples.slots.index', $templeId)->with('success', 'Darshan slot deleted successfully!');
    }
}
