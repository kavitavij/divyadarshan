<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DarshanSlot;
use App\Models\Temple;
use Illuminate\Http\Request;

class DarshanSlotController extends Controller
{
    /**
     * Display the slot management form for a specific temple.
     */
    public function index(Temple $temple)
    {
        // Eager load the slots to make it efficient
        $slots = $temple->darshanSlots()->orderBy('slot_date', 'desc')->get();
        return view('admin.slots.index', compact('temple', 'slots'));
    }

    /**
     * Store a new darshan slot in the database.
     */
    public function store(Request $request, Temple $temple)
    {
        $request->validate([
            'slot_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'total_capacity' => 'required|integer|min:1',
        ]);

        DarshanSlot::create([
            'temple_id' => $temple->id,
            'slot_date' => $request->slot_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'total_capacity' => $request->total_capacity,
        ]);

        return back()->with('success', 'Slot created successfully!');
    }
}