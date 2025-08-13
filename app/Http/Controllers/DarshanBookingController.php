<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Temple; 
use Carbon\Carbon;
use App\Models\DarshanSlot;

class DarshanBookingController extends Controller
{
    /**
     * Show the booking form.
     */
    public function index()
    {
        // 2. FETCH ALL TEMPLES FROM THE DATABASE
        $temples = Temple::orderBy('name')->get();

        // 3. PASS THE '$temples' VARIABLE TO THE VIEW
        return view('booking.index', ['temples' => $temples]);
    }
   
    public function getSlots(Request $request, Temple $temple)
    {
        $request->validate(['date' => 'required|date']);

        // Get the date the user selected, in 'YYYY-MM-DD' format
        $requestedDate = Carbon::parse($request->input('date'), 'Asia/Kolkata')->toDateString();

        // Get the slot data from the JSON column (it will now be a PHP array)
        $slotData = $temple->slot_data;

        // Check if the date exists in the array and if its value is 'available'
        if (isset($slotData[$requestedDate]) && $slotData[$requestedDate] === 'available') {

            // Since availability is for the whole day, we create some example slots to show the user.
            // You can customize these time slots as needed.
            $mockSlots = [
                ['id' => 1, 'start_time_formatted' => '09:00 AM', 'end_time_formatted' => '11:00 AM', 'total_capacity' => 1000, 'booked_capacity' => 0],
                ['id' => 2, 'start_time_formatted' => '11:00 AM', 'end_time_formatted' => '01:00 PM', 'total_capacity' => 1000, 'booked_capacity' => 0],
                ['id' => 3, 'start_time_formatted' => '03:00 PM', 'end_time_formatted' => '05:00 PM', 'total_capacity' => 1000, 'booked_capacity' => 0],
            ];

            return response()->json($mockSlots);
        }

        return response()->json([]);
    }
}

