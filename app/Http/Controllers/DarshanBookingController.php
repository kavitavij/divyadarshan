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
    /**
     * Display the booking page.
     */
    public function index(Request $request)
    {
        $temples = Temple::orderBy('name')->get();
        $selectedTemple = null;
        $calendars = [];
        $slots = collect(); // Use an empty collection
        $selectedDate = null;

        if ($request->has('temple_id')) {
            $selectedTemple = Temple::find($request->input('temple_id'));

            if ($selectedTemple) {
                $calendars = $this->generateCalendarData($selectedTemple);

                if ($request->has('selected_date')) {
                    $selectedDate = Carbon::parse($request->selected_date);
                    
                    // First, try to find real, custom slots created by the admin.
                    $slots = DarshanSlot::where('temple_id', $selectedTemple->id)
                        ->where('slot_date', $selectedDate->toDateString())
                        ->get();

                    // If NO custom slots were found, check the daily availability.
                    if ($slots->isEmpty()) {
                        $dateString = $selectedDate->toDateString();
                        $slotData = $selectedTemple->slot_data ?? [];

                        $dayStatus = 'available';
                        if (isset($slotData[$dateString])) {
                            $dayStatus = $slotData[$dateString];
                        }
                        if ($selectedDate->isPast() && !$selectedDate->isToday()) {
                            $dayStatus = 'not_available';
                        }

                        // If the day is available, create the default slots.
                        if ($dayStatus === 'available') {
                            // THE FIX: Changed from (object)[] to [] to create arrays
                            $slots = collect([
                                [
                                    'id' => 'default_1',
                                    'start_time_formatted' => '09:00 AM',
                                    'end_time_formatted' => '11:00 AM',
                                    'available_capacity' => 1000,
                                ],
                                [
                                    'id' => 'default_2',
                                    'start_time_formatted' => '11:00 AM',
                                    'end_time_formatted' => '01:00 PM',
                                    'available_capacity' => 1000,
                                ],
                                [
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
            }
        }

        return view('booking.index', compact('temples', 'selectedTemple', 'calendars', 'slots', 'selectedDate'));
    }

    /**
     * Show the form for entering user details.
     */
    public function details(Request $request)
    {
        if ($request->isMethod('get')) {
            return redirect()->route('booking.index')->with('error', 'There was a problem with your selection. Please try again.');
        }

        $bookingData = $request->validate([
            'temple_id' => 'required|exists:temples,id',
            'darshan_slot_id' => 'required', // Can be integer or string
            'number_of_people' => 'required|integer|min:1|max:5',
        ]);

        return view('booking.details', compact('bookingData'));
    }

    /**
     * Store the booking details and redirect to the summary page.
     */
    public function store(Request $request)
    {
        $request->validate([
            'temple_id' => 'required|exists:temples,id',
            'darshan_slot_id' => 'required',
            'number_of_people' => 'required|integer|min:1|max:5',
            'devotees' => 'required|array',
        ]);

        // If the slot ID is one of the defaults, we don't save it as a foreign key.
        // In a real system, you might create a "default" slot record on the fly here.
        $slotIdToSave = is_numeric($request->darshan_slot_id) ? $request->darshan_slot_id : null;

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'temple_id' => $request->temple_id,
            'darshan_slot_id' => $slotIdToSave,
            'number_of_people' => $request->number_of_people,
            'status' => 'Pending Payment',
            'devotee_details' => $request->devotees,
        ]);

        return redirect()->route('booking.summary', $booking);
    }

    /**
     * Display the booking summary page.
     */
    public function summary(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }
        $booking->load('temple');
        return view('booking.summary', compact('booking'));
    }

    /**
     * Display the mock payment page.
     */
    public function payment(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }
        $booking->load('temple');
        $amount = $booking->number_of_people * 100; // Example fee
        $confirmRoute = route('booking.confirm');
        return view('shared.payment', compact('booking', 'amount', 'confirmRoute'));
    }

    /**
     * This is the final confirmation step after the "payment" is made.
     */
    public function confirmBooking(Request $request)
    {
        $request->validate(['booking_id' => 'required|exists:bookings,id']);
        $booking = Booking::findOrFail($request->booking_id);

        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        // If it was a real slot, update its booked capacity
        if ($booking->darshan_slot_id) {
            $slot = DarshanSlot::find($booking->darshan_slot_id);
            if ($slot) {
                $slot->booked_capacity += $booking->number_of_people;
                $slot->save();
            }
        }

        $booking->status = 'Confirmed';
        $booking->save();

        return redirect()->route('home')->with('success', 'Your payment was successful and your Darshan has been confirmed!');
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
}
