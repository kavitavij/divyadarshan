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
use Illuminate\Support\Arr;
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
        // This validation is correct
        $validatedData = $request->validate([ /* ... */ ]);

        $slotIdString = $validatedData['darshan_slot_id'];
        $numberOfPeople = (int)$validatedData['number_of_people'];
        $booking = null;

        DB::transaction(function () use ($validatedData, $slotIdString, $numberOfPeople, &$booking) {
            $activeLine = null;
            if (str_starts_with($slotIdString, 'default-')) {
                $defaultSlotId = (int)str_replace('default-', '', $slotIdString);
                $slot = DefaultDarshanSlot::findOrFail($defaultSlotId);
                $lineCapacities = $this->getLineCapacities($slot->capacity);
                $bookedCounts = Booking::where('default_darshan_slot_id', $slot->id)->where('booking_date', $validatedData['selected_date'])
                    ->groupBy('line_number')->pluck(DB::raw('SUM(number_of_people)'), 'line_number');
                for ($line = 1; $line <= 3; $line++) {
                    if ($bookedCounts->get($line, 0) + $numberOfPeople <= $lineCapacities[$line]) {
                        $activeLine = $line;
                        break;
                    }
                }
                if (!$activeLine) throw new \Exception('Sorry, the selected time slot is now full.');
                $booking = Booking::create([
                    'user_id' => Auth::id(), 'temple_id' => $validatedData['temple_id'],
                    'default_darshan_slot_id' => $defaultSlotId, 'booking_date' => $validatedData['selected_date'],
                    'time_slot' => date('g:i A', strtotime($slot->start_time)), 'line_number' => $activeLine,
                    'number_of_people' => $numberOfPeople, 'status' => 'Pending Payment',
                    'devotee_details' => $validatedData['devotees'],
                ]);
            // ** THIS IS THE FIX: Added the missing closing brace **
            } else {
                $slotId = (int)$slotIdString;
                $slot = DarshanSlot::where('id', $slotId)->lockForUpdate()->firstOrFail();
                $lineCapacities = $this->getLineCapacities($slot->total_capacity);
                $bookedCounts = Booking::where('darshan_slot_id', $slot->id)->where('booking_date', $validatedData['selected_date'])
                    ->groupBy('line_number')->pluck(DB::raw('SUM(number_of_people)'), 'line_number');
                for ($line = 1; $line <= 3; $line++) {
                    if ($bookedCounts->get($line, 0) + $numberOfPeople <= $lineCapacities[$line]) {
                        $activeLine = $line;
                        break;
                    }
                }
                if (!$activeLine) throw new \Exception('Sorry, the selected time slot is now full.');
                $booking = Booking::create([
                    'user_id' => Auth::id(), 'temple_id' => $validatedData['temple_id'],
                    'darshan_slot_id' => $slotId, 'booking_date' => $validatedData['selected_date'],
                    'time_slot' => date('g:i A', strtotime($slot->start_time)), 'line_number' => $activeLine,
                    'number_of_people' => $numberOfPeople, 'status' => 'Pending Payment',
                    'devotee_details' => $validatedData['devotees'],
                ]);
            }
        });

        // The try/catch block was removed for brevity, but you should keep it
        return redirect()->route('booking.summary', $booking->id);
    }
    public function getSlotsForDate(Temple $temple, $date)
    {
        // 1. Check if the day is closed
        $dayStatus = TempleDayStatus::where('temple_id', $temple->id)->where('date', $date)->where('is_closed', true)->first();
        if ($dayStatus) {
            return response()->json(['closed' => true, 'reason' => $dayStatus->reason]);
        }

        // 2. Check for overrides
        $overrides = DarshanSlot::where('temple_id', $temple->id)->where('slot_date', $date)->orderBy('start_time')->get();
        if ($overrides->isNotEmpty()) {
            $slots = $overrides->map(function ($slot) use ($date) {
                $lineCapacities = $this->getLineCapacities($slot->total_capacity);
                $bookedCounts = Booking::where('darshan_slot_id', $slot->id)->where('booking_date', $date)
                    ->select('line_number', DB::raw('SUM(number_of_people) as total_booked'))
                    ->groupBy('line_number')->pluck('total_booked', 'line_number');

                for ($line = 1; $line <= 3; $line++) {
                    $booked = $bookedCounts->get($line, 0);
                    if ($booked < $lineCapacities[$line]) {
                        return ['id' => $slot->id, 'time' => date('g:i A', strtotime($slot->start_time)), 'available' => $lineCapacities[$line] - $booked, 'line' => $line, 'sold_out' => false];
                    }
                }
                return ['id' => $slot->id, 'time' => date('g:i A', strtotime($slot->start_time)), 'available' => 0, 'line' => 'Full', 'sold_out' => true];
            })->values();
            return response()->json($slots);
        }

        // 3. Fall back to defaults
        $defaultSlots = DefaultDarshanSlot::where('temple_id', $temple->id)->where('is_active', true)->orderBy('start_time')->get();
        $slots = $defaultSlots->map(function ($slot) use ($date) {
            $lineCapacities = $this->getLineCapacities($slot->capacity);
            $bookedCounts = Booking::where('default_darshan_slot_id', $slot->id)->where('booking_date', $date)
                ->select('line_number', DB::raw('SUM(number_of_people) as total_booked'))
                ->groupBy('line_number')->pluck('total_booked', 'line_number');

            for ($line = 1; $line <= 3; $line++) {
                $booked = $bookedCounts->get($line, 0);
                if ($booked < $lineCapacities[$line]) {
                    return ['id' => 'default-'.$slot->id, 'time' => date('g:i A', strtotime($slot->start_time)), 'available' => $lineCapacities[$line] - $booked, 'line' => $line, 'sold_out' => false];
                }
            }
            // THIS IS THE FIX: Added sold out logic for default slots too
            return ['id' => 'default-'.$slot->id, 'time' => date('g:i A', strtotime($slot->start_time)), 'available' => 0, 'line' => 'Full', 'sold_out' => true];
        })->values();
        return response()->json($slots);
    }
    private function getLineCapacities(int $totalCapacity): array
    {
        $lines = 3;
        $baseCapacity = floor($totalCapacity / $lines);
        $remainder = $totalCapacity % $lines;
        $capacities = [];
        for ($i = 1; $i <= $lines; $i++) {
            $capacities[$i] = $baseCapacity + ($i <= $remainder ? 1 : 0);
        }
        return $capacities;
    }
}
