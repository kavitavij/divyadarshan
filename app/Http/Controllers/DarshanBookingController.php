<?php

namespace App\Http\Controllers;

use App\Models\Temple;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DarshanBookingController extends Controller
{
    // ... your index() and generateCalendarData() methods remain the same ...
    public function index(Request $request)
    {
        $temples = Temple::orderBy('name')->get();
        $selectedTemple = null;
        $calendars = [];
        $slots = [];
        $selectedDate = null;

        if ($request->has('temple_id')) {
            $selectedTemple = Temple::find($request->input('temple_id'));

            if ($selectedTemple) {
                $calendars = $this->generateCalendarData($selectedTemple);

                if ($request->has('selected_date')) {
                    $selectedDate = Carbon::parse($request->selected_date);
                    $dateString = $selectedDate->toDateString();
                    $slotData = $selectedTemple->slot_data ?? [];

                    $dayStatus = 'available';
                    if (isset($slotData[$dateString])) {
                        $dayStatus = $slotData[$dateString];
                    }
                    if ($selectedDate->isPast() && !$selectedDate->isToday()) {
                        $dayStatus = 'not_available';
                    }

                    if ($dayStatus === 'available') {
                        $slots = [
                            ['id' => 1, 'start_time_formatted' => '09:00 AM', 'end_time_formatted' => '11:00 AM'],
                            ['id' => 2, 'start_time_formatted' => '11:00 AM', 'end_time_formatted' => '01:00 PM'],
                            ['id' => 3, 'start_time_formatted' => '03:00 PM', 'end_time_formatted' => '05:00 PM'],
                        ];
                    }
                }
            }
        }

        return view('booking.index', compact('temples', 'selectedTemple', 'calendars', 'slots', 'selectedDate'));
    }

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
                } elseif (isset($slotData[$dateString])) {
                    $status = $slotData[$dateString];
                } else {
                    $status = 'available';
                }

                $days[] = ['day' => $day, 'date' => $dateString, 'status' => $status];
            }

            $calendars[] = ['month_name' => $monthName, 'days' => $days];
            $currentDate->addMonth();
        }

        return $calendars;
    }


    /**
     * NEW METHOD: Show the form for entering user details.
     */
    public function details(Request $request)
    {
        // If it's a GET request, it's an error. Redirect them.
        if ($request->isMethod('get')) {
            return redirect()->route('booking.index')->with('error', 'There was a problem with your selection. Please try again.');
        }

        // If it's a POST request, proceed with validation as normal.
        $bookingData = $request->validate([
            'temple_id' => 'required|exists:temples,id',
            'darshan_slot_id' => 'required|integer',
            'number_of_people' => 'required|integer|min:1|max:5',
        ]);

        return view('booking.details', compact('bookingData'));
    }
    /**
     * UPDATED METHOD: Store the final booking after details are entered.
     */
    public function store(Request $request)
{
    $request->validate([
        'temple_id' => 'required|exists:temples,id',
        'darshan_slot_id' => 'required|integer',
        'number_of_people' => 'required|integer|min:1|max:5',
        'devotees' => 'required|array',
        'devotees.*.first_name' => 'required|string|max:255',
        // Add validation for your other devotee fields...
    ]);

    // Create the booking
    $booking = Booking::create([
        'user_id' => Auth::id(),
        'temple_id' => $request->temple_id,
        'darshan_slot_id' => $request->darshan_slot_id,
        'number_of_people' => $request->number_of_people,
        'status' => 'Pending Payment', // New status
        'devotee_details' => $request->devotees,
    ]);

    // Redirect to the new summary page with the booking ID
    return redirect()->route('booking.summary', ['booking' => $booking->id]);
}

/**
 * NEW METHOD: Display the booking summary page.
 */
public function summary(Booking $booking)
{
    // Ensure the user can only view their own bookings
    if ($booking->user_id !== Auth::id()) {
        abort(403);
    }

    // Eager load the temple relationship to get the temple name
    $booking->load('temple');

    return view('booking.summary', compact('booking'));
}
}