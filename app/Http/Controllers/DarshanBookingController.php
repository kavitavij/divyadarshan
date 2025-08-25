<?php

namespace App\Http\Controllers;

use App\Models\Temple;
use App\Models\Booking;
use App\Models\DarshanSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DarshanBookingController extends Controller
{
    public function index(Request $request)
    {
        $temples = Temple::orderBy('name')->get();
        $selectedTemple = null;
        $slots = collect();
        $selectedDate = null;
        $calendars = [];

        if ($request->has('temple_id')) {
            $selectedTemple = Temple::findOrFail($request->input('temple_id'));
            $calendars = $this->generateCalendarData($selectedTemple);

            if ($request->has('selected_date')) {
                $selectedDate = Carbon::parse($request->selected_date);
                $slots = $this->getAvailableSlots($selectedTemple, $selectedDate);
            }
        }

        return view('booking.index', compact('temples', 'selectedTemple', 'calendars', 'slots', 'selectedDate'));
    }
    //     public function details(Request $request)
    // {
    //     $validated = $request->validate([
    //         'temple_id' => 'required|exists:temples,id',
    //         'darshan_slot_id' => 'required|string',
    //         'selected_date' => 'required|date',
    //         'slot_details' => 'required|string',
    //         'number_of_people' => 'required|integer|min:1|max:10',
    //     ]);


    //     $temple = Temple::findOrFail($validated['temple_id']);
    //     $chargePerPerson = $temple->darshan_charge ?? 0;


    //     $validated['darshan_charge'] = $chargePerPerson;
    //     $validated['total_charge'] = $chargePerPerson * $validated['number_of_people'];

    //     return view('booking.details', ['bookingData' => $validated,'temple' => $temple,]);
    // }


    public function details(Request $request)
{
    $validated = $request->validate([
        'temple_id' => 'required|exists:temples,id',
        'darshan_slot_id' => 'required|string',
        'selected_date' => 'required|date',
        'slot_details' => 'required|string',
        'number_of_people' => 'required|integer|min:1|max:10',
    ]);

    $temple = Temple::findOrFail($validated['temple_id']);
    $chargePerPerson = $temple->darshan_charge ?? 0;

    $validated['darshan_charge'] = $chargePerPerson;
    $validated['total_charge'] = $chargePerPerson * $validated['number_of_people'];

    return view('booking.details', [
        'bookingData' => $validated,
        'temple' => $temple,
    ]);
}

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'temple_id' => 'required|exists:temples,id',
            'darshan_slot_id' => 'required|string',
            'selected_date' => 'required|date',
            'number_of_people' => 'required|integer|min:1',
            'devotees' => 'required|array',
            'devotees.*.first_name' => 'required|string|max:255',
            'devotees.*.last_name' => 'required|string|max:255',
            'devotees.*.age' => 'required|integer|min:1',
            'devotees.*.phone_number' => 'required|string|max:15',
            'devotees.*.id_type' => 'required|string',
            'devotees.*.id_number' => 'required|string',
        ]);

        $slotIdToSave = is_numeric($validatedData['darshan_slot_id']) ? $validatedData['darshan_slot_id'] : null;

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'temple_id' => $validatedData['temple_id'],
            'darshan_slot_id' => $slotIdToSave,
            'booking_date' => $validatedData['selected_date'],
            'number_of_people' => $validatedData['number_of_people'],
            'status' => 'Pending Payment',
            'devotee_details' => $validatedData['devotees'],
        ]);

        return redirect()->route('payment.create', ['type' => 'darshan', 'id' => $booking->id]);
    }
    // Helper function to get slots for a given day
    private function getAvailableSlots(Temple $temple, Carbon $date)
    {
        $slots = DarshanSlot::where('temple_id', $temple->id)
            ->where('slot_date', $date->toDateString())
            ->get();

        if ($slots->isEmpty()) {
            return collect([
                ['id' => 'default_09:00-11:00', 'time' => '09:00 AM - 11:00 AM', 'capacity' => 1000],
                ['id' => 'default_11:00-13:00', 'time' => '11:00 AM - 01:00 PM', 'capacity' => 1000],
                ['id' => 'default_15:00-17:00', 'time' => '03:00 PM - 05:00 PM', 'capacity' => 1000],
            ]);
        }

        return $slots->map(function ($slot) {
            return [
                'id' => $slot->id,
                'time' => Carbon::parse($slot->start_time)->format('h:i A') . ' - ' . Carbon::parse($slot->end_time)->format('h:i A'),
                'capacity' => $slot->total_capacity - $slot->booked_capacity,
            ];
        });
    }

    // Helper function to generate calendar data
    private function generateCalendarData(Temple $temple)
    {
        $calendars = [];
        $currentDate = Carbon::now()->startOfMonth();
        $slotData = $temple->slot_data ?? [];

        for ($i = 0; $i < 3; $i++) {
            $monthName = $currentDate->format('F Y');
            $daysInMonth = $currentDate->daysInMonth;
            $startOfMonth = $currentDate->copy()->startOfMonth()->dayOfWeek;
            $days = array_fill(0, $startOfMonth, null);

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = $currentDate->copy()->setDay($day);
                $dateString = $date->toDateString();
                $status = 'available';

                if ($date->isPast() && !$date->isToday()) {
                    $status = 'not_available';
                } elseif (isset($slotData[$dateString])) {
                    $status = $slotData[$dateString];
                }
                $days[] = ['day' => $day, 'date' => $dateString, 'status' => $status];
            }
            $calendars[] = ['month_name' => $monthName, 'days' => $days];
            $currentDate->addMonth();
        }
        return $calendars;
    }
}
