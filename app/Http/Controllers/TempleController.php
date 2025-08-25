<?php

namespace App\Http\Controllers;

use App\Models\Temple;
use App\Models\DarshanSlot;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TempleController extends Controller
{
    /**
     * Display the specified temple page.
     */
    public function show(Request $request, Temple $temple)
    {
        $calendars = $this->generateCalendarData($temple);
        $slots = collect();
        $selectedDate = null;

        if ($request->has('selected_date')) {
            $selectedDate = Carbon::parse($request->selected_date);

            // First, try to find real, custom slots created by the admin for this date.
            $slots = DarshanSlot::where('temple_id', $temple->id)
                ->where('slot_date', $selectedDate->toDateString())
                ->get();

            // If NO custom slots were found, check the daily availability.
            if ($slots->isEmpty()) {
                $dateString = $selectedDate->toDateString();
                $slotData = $temple->slot_data ?? [];

                $dayStatus = 'available';
                if (isset($slotData[$dateString])) {
                    $dayStatus = $slotData[$dateString];
                }
                if ($selectedDate->isPast() && !$selectedDate->isToday()) {
                    $dayStatus = 'not_available';
                }

                // If the day is available, create the default slots.
                if ($dayStatus === 'available') {
                    $slots = collect([
                        (object)[
                            'id' => 'default_1', // Use a string to differentiate from real IDs
                            'start_time_formatted' => '09:00 AM',
                            'end_time_formatted' => '11:00 AM',
                            'available_capacity' => 1000,
                        ],
                        (object)[
                            'id' => 'default_2',
                            'start_time_formatted' => '11:00 AM',
                            'end_time_formatted' => '01:00 PM',
                            'available_capacity' => 1000,
                        ],
                        (object)[
                            'id' => 'default_3',
                            'start_time_formatted' => '03:00 PM',
                            'end_time_formatted' => '05:00 PM',
                            'available_capacity' => 1000,
                        ],
                    ]);
                }
            } else {
                // If real slots WERE found, calculate their live capacity.
                $slots = $slots->map(function ($slot) {
                    $slot->start_time_formatted = Carbon::parse($slot->start_time)->format('h:i A');
                    $slot->end_time_formatted = Carbon::parse($slot->end_time)->format('h:i A');
                    $slot->available_capacity = $slot->total_capacity - $slot->booked_capacity;
                    return $slot;
                });
            }
        }

        return view('temples.show', compact('temple', 'calendars', 'slots', 'selectedDate'));
    }

    /**
     * Generates calendar data for the public temple page.
     */
    private function generateCalendarData(Temple $temple)
    {
        $calendars = [];
        $currentDate = Carbon::now()->startOfMonth();
        $slotData = $temple->slot_data ?? [];

        for ($i = 0; $i < 4; $i++) {
            $monthName = $currentDate->format('F Y');
            $daysInMonth = $currentDate->daysInMonth;
            $startOfMonth = $currentDate->copy()->startOfMonth()->dayOfWeek;
            $days = array_fill(0, $startOfMonth, null);

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = $currentDate->copy()->setDay($day);
                $dateString = $date->toDateString();

                if ($date->isPast() && !$date->isToday()) {
                    $status = 'not_available';
                }
                elseif (isset($slotData[$dateString])) {
                    $status = $slotData[$dateString];
                }
                else {
                    $status = 'available';
                }

                $days[] = ['day' => $day, 'date' => $dateString, 'status' => $status];
            }

            $calendars[] = ['month_name' => $monthName, 'days' => $days];
            $currentDate->addMonth();
        }

        return $calendars;
    }
    public function details(Request $request)
{
    $bookingData = [
        'temple_id' => $request->input('temple_id'),
        'selected_date' => $request->input('selected_date'),
        'darshan_slot_id' => $request->input('darshan_slot_id'),
        'number_of_people' => $request->input('number_of_people'),
    ];

    return view('booking.details', compact('bookingData'));
}

}
