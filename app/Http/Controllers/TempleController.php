<?php

namespace App\Http\Controllers;

use App\Models\Temple;
use App\Models\DarshanSlot;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\SocialServiceInquiryMail;
use Exception;
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
        // 1. Find the temple in the database using the ID from the form
        $temple = Temple::findOrFail($request->input('temple_id'));

        $bookingData = [
            'temple_id' => $request->input('temple_id'),
            'selected_date' => $request->input('selected_date'),
            'darshan_slot_id' => $request->input('darshan_slot_id'),
            'number_of_people' => $request->input('number_of_people'),
        ];

        // 2. Pass BOTH the $bookingData AND the $temple object to the view
        return view('temples.details', compact('bookingData', 'temple'));
    }
    public function storeSocialServiceInquiry(Request $request)
    {
        $validated = $request->validate([
            'service_type' => 'required|string|in:annadaan,health_camps,education_aid,environment_care,community_seva',
            'temple_id' => 'required|exists:temples,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:15',
            'message' => 'nullable|string|max:1000',
        ]);

        try {
            $temple = Temple::findOrFail($validated['temple_id']);

            $recipients = [];

            if (env('ADMIN_EMAIL')) {
                $recipients[] = env('ADMIN_EMAIL');
            }

            // 2. Add the temple manager's email, if it exists
            if (!empty($temple->manager_email)) {
                $recipients[] = $temple->manager_email;
            }
            $recipients = array_unique($recipients);

            if (!empty($recipients)) {
                // Send the email
                Mail::to($recipients)->send(new SocialServiceInquiryMail($validated, $temple));
            } else {
                Log::warning('No recipients found for social service inquiry email.', ['temple_id' => $temple->id]);
            }

        } catch (Exception $e) {
            Log::error('Failed to send social service inquiry email: ' . $e->getMessage());
        }
        return back()->with('success', 'Thank you for your interest! Our team will review your inquiry and contact you shortly.');
    }
}

