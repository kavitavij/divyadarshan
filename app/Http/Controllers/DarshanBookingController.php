<?php

namespace App\Http\Controllers;

use App\Models\Temple;
use App\Models\Booking;
use App\Models\DarshanSlot;
use App\Models\DefaultDarshanSlot;
use App\Models\TempleDayStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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

    public function details(Request $request)
    {
        $validatedData = $request->validate([
            'temple_id' => 'required|exists:temples,id',
            'selected_date' => 'required|date',
            'number_of_people' => 'required|integer|min:1',
            // --- THIS IS THE CORRECTED VALIDATION LOGIC ---
            'darshan_slot_id' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (str_starts_with($value, 'default-')) {
                        $id = (int) str_replace('default-', '', $value);
                        if (!DB::table('default_darshan_slots')->where('id', $id)->exists()) {
                            $fail('The selected darshan slot id is invalid.');
                        }
                    } else {
                        if (!DB::table('darshan_slots')->where('id', $value)->exists()) {
                            $fail('The selected darshan slot id is invalid.');
                        }
                    }
                },
            ],
        ]);

        $temple = Temple::findOrFail($validatedData['temple_id']);
        $slotIdString = $validatedData['darshan_slot_id'];
        $darshanSlot = null;

        if (str_starts_with($slotIdString, 'default-')) {
            $defaultSlotId = (int)str_replace('default-', '', $slotIdString);
            $darshanSlot = DefaultDarshanSlot::findOrFail($defaultSlotId);
        } else {
            $darshanSlot = DarshanSlot::findOrFail((int)$slotIdString);
        }

        return view('booking.details', [
            'bookingData' => $validatedData,
            'temple' => $temple,
            'darshanSlot' => $darshanSlot,
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'temple_id' => 'required|exists:temples,id',
            'selected_date' => 'required|date',
            'number_of_people' => 'required|integer|min:1',
            'devotees' => 'required|array',
            'devotees.*.full_name' => 'required|string|max:255',
            // --- THIS IS THE CORRECTED VALIDATION LOGIC ---
             'darshan_slot_id' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (str_starts_with($value, 'default-')) {
                        $id = (int) str_replace('default-', '', $value);
                        if (!DB::table('default_darshan_slots')->where('id', $id)->exists()) {
                            $fail('The selected darshan slot id is invalid.');
                        }
                    } else {
                        if (!DB::table('darshan_slots')->where('id', $value)->exists()) {
                            $fail('The selected darshan slot id is invalid.');
                        }
                    }
                },
            ],
        ]);

        // ... The rest of your store method is correct and remains unchanged ...
        $slotIdString = $validatedData['darshan_slot_id'];
        $numberOfPeople = (int)$validatedData['number_of_people'];
        $booking = null;
        $timeSlotInfo = null;

        if (str_starts_with($slotIdString, 'default-')) {
            $defaultSlotId = (int)str_replace('default-', '', $slotIdString);
            $defaultSlot = DefaultDarshanSlot::findOrFail($defaultSlotId);
            $timeSlotInfo = date('g:i A', strtotime($defaultSlot->start_time));
            $bookedCount = Booking::where('default_darshan_slot_id', $defaultSlotId)
                                  ->where('booking_date', $validatedData['selected_date'])
                                  ->sum('number_of_people');
            if (($defaultSlot->capacity - $bookedCount) < $numberOfPeople) {
                return redirect()->back()->with('error', 'Sorry, not enough slots are available for that time.')->withInput();
            }
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'temple_id' => $validatedData['temple_id'],
                'default_darshan_slot_id' => $defaultSlotId,
                'booking_date' => $validatedData['selected_date'],
                'time_slot' => $timeSlotInfo,
                'number_of_people' => $numberOfPeople,
                'status' => 'Pending Payment',
                'devotee_details' => $validatedData['devotees'],
            ]);
        } else {
            $slotId = (int)$slotIdString;
            try {
                DB::transaction(function () use ($validatedData, $numberOfPeople, $slotId, &$booking, &$timeSlotInfo) {
                    $slot = DarshanSlot::where('id', $slotId)->lockForUpdate()->firstOrFail();
                    $timeSlotInfo = date('g:i A', strtotime($slot->start_time));
                    if (($slot->total_capacity - $slot->booked_capacity) < $numberOfPeople) {
                        throw new \Exception('Sorry, not enough slots are available.');
                    }
                    $booking = Booking::create([
                        'user_id' => Auth::id(),
                        'temple_id' => $validatedData['temple_id'],
                        'darshan_slot_id' => $slotId,
                        'booking_date' => $validatedData['selected_date'],
                        'time_slot' => $timeSlotInfo,
                        'number_of_people' => $numberOfPeople,
                        'status' => 'Pending Payment',
                        'devotee_details' => $validatedData['devotees'],
                    ]);
                    $slot->booked_capacity += $numberOfPeople;
                    $slot->save();
                });
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage())->withInput();
            }
        }
        return redirect()->route('booking.summary', $booking->id);
    }

    public function getSlotsForDate(Temple $temple, $date)
    {
        $dayStatus = TempleDayStatus::where('temple_id', $temple->id)
                                    ->where('date', $date)->where('is_closed', true)->first();
        if ($dayStatus) {
            return response()->json(['closed' => true, 'reason' => $dayStatus->reason]);
        }
        $overrides = DarshanSlot::where('temple_id', $temple->id)
                                ->where('slot_date', $date)->get();
        if ($overrides->isNotEmpty()) {
            $slots = $overrides->map(function ($slot) {
                $available = $slot->total_capacity - $slot->booked_capacity;
                if ($available > 0) {
                    return ['id' => $slot->id, 'time' => date('g:i A', strtotime($slot->start_time)), 'available' => $available];
                }
            })->filter()->values();
            return response()->json($slots);
        }
        $defaultSlots = DefaultDarshanSlot::where('temple_id', $temple->id)
                                        ->where('is_active', true)->orderBy('start_time')->get();
        $bookedCounts = Booking::where('temple_id', $temple->id)
            ->where('booking_date', $date)
            ->whereNotNull('default_darshan_slot_id')
            ->select('default_darshan_slot_id', DB::raw('SUM(number_of_people) as total_booked'))
            ->groupBy('default_darshan_slot_id')
            ->pluck('total_booked', 'default_darshan_slot_id');
        $slots = $defaultSlots->map(function ($slot) use ($bookedCounts) {
            $booked = $bookedCounts->get($slot->id, 0);
            $available = $slot->capacity - $booked;
            if ($available > 0) {
                return ['id' => 'default-'.$slot->id, 'time' => date('g:i A', strtotime($slot->start_time)), 'available' => $available];
            }
        })->filter()->values();
        return response()->json($slots);
    }
}
