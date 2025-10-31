<?php

namespace App\Http\Controllers;

use App\Models\Temple;
use App\Models\Booking;
use App\Models\DarshanSlot;
use App\Models\DefaultDarshanSlot;
use App\Models\TempleDayStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DarshanBookingController extends Controller
{
    public function index(Request $request)
    {
        $temples = Temple::orderBy('name')->get();
        $selectedTemple = null;
        $calendars = [];

        if ($request->has('temple_id')) {
            $selectedTemple = Temple::findOrFail($request->input('temple_id'));
            $calendars = $this->generateCalendarData($selectedTemple);
        }
        return view('booking.index', compact('temples', 'selectedTemple', 'calendars'));
    }

    private function generateCalendarData(Temple $temple)
    {
        $calendars = [];
        $currentDate = Carbon::now()->startOfMonth();

        for ($i = 0; $i < 5; $i++) {
            $monthName = $currentDate->format('F Y');
            $daysInMonth = $currentDate->daysInMonth;
            $startOfMonth = $currentDate->copy()->startOfMonth()->dayOfWeek;
            $days = array_fill(0, $startOfMonth, null);

            $closedDates = TempleDayStatus::where('temple_id', $temple->id)
                ->where('is_closed', true)
                ->whereMonth('date', $currentDate->month)
                ->whereYear('date', $currentDate->year)
                ->pluck('date')
                ->map(fn($date) => Carbon::parse($date)->toDateString())
                ->toArray();

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = $currentDate->copy()->setDay($day);
                $dateString = $date->toDateString();
                $status = ($date->isPast() && !$date->isToday()) || in_array($dateString, $closedDates) ? 'not_available' : 'available';
                $days[] = ['day' => $day, 'date' => $dateString, 'status' => $status];
            }

            $calendars[] = ['month_name' => $monthName, 'days' => $days];
            $currentDate->addMonth();
        }
        return $calendars;
    }

    public function getSlotsForDate(Temple $temple, $date)
    {
        $dayStatus = TempleDayStatus::where('temple_id', $temple->id)
            ->where('date', $date)->where('is_closed', true)->first();
        if ($dayStatus) {
            return response()->json(['closed' => true, 'reason' => $dayStatus->reason]);
        }

        $overrides = DarshanSlot::where('temple_id', $temple->id)
            ->where('slot_date', $date)->orderBy('start_time')->get();

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

        $defaultSlots = DefaultDarshanSlot::where('temple_id', $temple->id)
            ->where('is_active', true)->orderBy('start_time')->get();

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
