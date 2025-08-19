<?php

namespace App\Http\Controllers;

use App\Models\Temple;
use App\Models\DarshanSlot; // Make sure to import the DarshanSlot model
use Illuminate\Http\Request;
use Carbon\Carbon;

class TempleController extends Controller
{
    /**
     * Display the specified temple page with its calendar and all slots.
     */
    public function show(Request $request, Temple $temple)
    {
        $calendars = $this->generateCalendarData($temple);
        $slots = collect(); // Use an empty collection by default
        $selectedDate = null;

        if ($request->has('selected_date')) {
            $selectedDate = Carbon::parse($request->selected_date);
            
            // This now fetches ALL real slots for the date from the database
            $slots = DarshanSlot::where('temple_id', $temple->id)
                ->where('slot_date', $selectedDate->toDateString())
                ->get()
                ->map(function ($slot) {
                    // Add formatted time and available capacity for the view
                    $slot->start_time_formatted = Carbon::parse($slot->start_time)->format('h:i A');
                    $slot->end_time_formatted = Carbon::parse($slot->end_time)->format('h:i A');
                    $slot->available_capacity = $slot->total_capacity - $slot->booked_capacity;
                    return $slot;
                });
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

            $calendars[] = [
                'month_name' => $monthName,
                'days' => $days,
            ];
            $currentDate->addMonth();
        }
        return $calendars;
    }
}
