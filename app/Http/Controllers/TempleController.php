<?php

namespace App\Http\Controllers;

use App\Models\Temple;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TempleController extends Controller
{
    /**
     * Display a listing of the temples.
     */
    public function index()
    {
        $temples = Temple::latest()->paginate(10);
        return view('temples.index', compact('temples'));
    }

    /**
     * Display the specified temple page with its calendar.
     */
    public function show(Request $request, Temple $temple)
    {
        $calendars = $this->generateCalendarData($temple);
        $slots = [];
        $selectedDate = null;

        // Check if a date was selected by the user
        if ($request->has('selected_date')) {
            $selectedDate = Carbon::parse($request->selected_date);
            $dateString = $selectedDate->toDateString();
            $slotData = $temple->slot_data ?? [];

            // Determine the final status of the selected date
            $dayStatus = 'available'; // Default
            if (isset($slotData[$dateString])) {
                $dayStatus = $slotData[$dateString];
            }
            if ($selectedDate->isPast() && !$selectedDate->isToday()) {
                $dayStatus = 'not_available';
            }

            // Only generate time slots if the final status is 'available'
            if ($dayStatus === 'available') {
                $slots = [
                    ['id' => 1, 'start_time_formatted' => '09:00 AM', 'end_time_formatted' => '11:00 AM'],
                    ['id' => 2, 'start_time_formatted' => '11:00 AM', 'end_time_formatted' => '01:00 PM'],
                    ['id' => 3, 'start_time_formatted' => '03:00 PM', 'end_time_formatted' => '05:00 PM'],
                ];
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
                
                // THE FIX IS HERE: The logic is re-ordered to be more explicit.
                
                // 1. First, check if the date is in the past. This has the highest priority.
                if ($date->isPast() && !$date->isToday()) {
                    $status = 'not_available';
                } 
                // 2. Next, check if a specific status was saved in the admin panel.
                elseif (isset($slotData[$dateString])) {
                    $status = $slotData[$dateString]; // This will be 'full' or 'not_available'
                } 
                // 3. If neither of the above is true, the date is available by default.
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
}
