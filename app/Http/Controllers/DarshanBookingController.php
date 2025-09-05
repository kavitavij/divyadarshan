<?php

namespace App\Http\Controllers;

use App\Models\Temple;
use App\Models\Booking;
use App\Models\DarshanSlot;
use App\Models\DefaultDarshanSlot;
use App\Models\TempleDayStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DarshanBookingController extends Controller
{
    public function index(Request $request)
    {
        $temples = Temple::orderBy('name')->get();
        $selectedTemple = null;
        if ($request->has('temple_id')) {
            $selectedTemple = Temple::findOrFail($request->input('temple_id'));
        }
        return view('booking.index', compact('temples', 'selectedTemple'));
    }

    // This is the new, simplified logic to get available slots
    public function getSlotsForDate(Temple $temple, $date)
    {
        // 1. Check if the day is marked as closed.
        $dayStatus = TempleDayStatus::where('temple_id', $temple->id)
            ->where('date', $date)->where('is_closed', true)->first();
        if ($dayStatus) {
            return response()->json(['closed' => true, 'reason' => $dayStatus->reason]);
        }

        // 2. Check for specific "override" slots for this date.
        $overrides = DarshanSlot::where('temple_id', $temple->id)
            ->where('slot_date', $date)->orderBy('start_time')->get();

        // If any overrides exist, show ONLY those.
        if ($overrides->isNotEmpty()) {
            $formattedSlots = $overrides->map(function ($slot) {
                $available = $slot->total_capacity - $slot->booked_capacity;
                if ($available > 0) {
                     return [
                        'id' => $slot->id,
                        'start_time' => date('g:i A', strtotime($slot->start_time)),
                        'end_time' => date('g:i A', strtotime($slot->end_time)),
                        'available' => $available
                    ];
                }
                return null;
            })->filter()->values();
            return response()->json($formattedSlots);
        }

        // 3. If no overrides, fall back to the temple's active default slots.
        $defaultSlots = DefaultDarshanSlot::where('temple_id', $temple->id)
            ->where('is_active', true)->orderBy('start_time')->get();

        // Calculate bookings for all default slots on the selected date
        $bookedCounts = Booking::where('temple_id', $temple->id)
            ->where('booking_date', $date)
            ->whereNotNull('default_darshan_slot_id')
            ->select('default_darshan_slot_id', DB::raw('SUM(number_of_people) as total_booked'))
            ->groupBy('default_darshan_slot_id')
            ->pluck('total_booked', 'default_darshan_slot_id');

        $formattedDefaults = $defaultSlots->map(function ($slot) use ($bookedCounts) {
            $booked = $bookedCounts->get($slot->id, 0);
            $available = $slot->capacity - $booked;
            if ($available > 0) {
                return [
                    'id' => 'default-' . $slot->id,
                    'start_time' => date('g:i A', strtotime($slot->start_time)),
                    'end_time' => date('g:i A', strtotime($slot->end_time)),
                    'available' => $available
                ];
            }
            return null;
        })->filter()->values();

        return response()->json($formattedDefaults);
    }
public function details(Request $request)
{
    $templeId = $request->input('temple_id');
    $slotId = $request->input('darshan_slot_id');
    $numberOfPeople = (int) $request->input('number_of_people');
    $date = $request->input('selected_date');

    $temple = Temple::findOrFail($templeId);

    // Validate number of people
    if ($numberOfPeople < 1) {
        return redirect()->back()->withErrors(['number_of_people' => 'Invalid number of devotees.']);
    }

    // Determine if slot is default or custom
    if (str_starts_with($slotId, 'default-')) {
        $slotIdNumeric = str_replace('default-', '', $slotId);
        $slot = DefaultDarshanSlot::where('id', $slotIdNumeric)
            ->where('is_active', true)
            ->first();
        if (!$slot) {
            return redirect()->back()->withErrors(['darshan_slot_id' => 'The selected darshan slot is invalid.']);
        }

        // Check availability
        $booked = Booking::where('temple_id', $templeId)
            ->where('booking_date', $date)
            ->where('default_darshan_slot_id', $slot->id)
            ->sum('number_of_people');

        if (($slot->capacity - $booked) < $numberOfPeople) {
            return redirect()->back()->withErrors(['darshan_slot_id' => 'The selected darshan slot does not have enough availability.']);
        }
    } else {
        $slot = DarshanSlot::where('id', $slotId)
            ->where('temple_id', $templeId)
            ->first();
        if (!$slot) {
            return redirect()->back()->withErrors(['darshan_slot_id' => 'The selected darshan slot is invalid.']);
        }

        // Check availability
        $available = $slot->total_capacity - $slot->booked_capacity;
        if ($available < $numberOfPeople) {
            return redirect()->back()->withErrors(['darshan_slot_id' => 'The selected darshan slot does not have enough availability.']);
        }
    }

    // Prepare booking data
    $bookingData = [
        'temple_id' => $temple->id,
        'temple_name' => $temple->name,
        'temple_city' => $temple->city,
        'temple_state' => $temple->state,
        'darshan_slot_id' => $slotId,
        'slot_details' => $slot,
        'number_of_people' => $numberOfPeople,
        'selected_date' => $date,
    ];

    $darshanSlot = $slot;

    return view('booking.details', compact('bookingData', 'temple', 'darshanSlot'));
}

}
