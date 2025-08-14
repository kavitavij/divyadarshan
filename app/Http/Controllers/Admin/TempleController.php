<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Temple;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TempleController extends Controller
{
    public function index()
    {
        $temples = Temple::latest()->paginate(10);
        return view('admin.temples.index', compact('temples'));
    }

    public function create()
    {
        // Pass an empty temple object and empty calendar data to the create view
        $temple = new Temple();
        $adminCalendars = $this->generateAdminCalendarData($temple);
        return view('admin.temples.create', compact('temple', 'adminCalendars'));
    }

    /**
     * Store a newly created temple in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'location', 'description', 'about', 'online_services', 'social_services']);

        // Process News Items
        $newsData = [];
        if ($request->has('news_items')) {
            foreach ($request->news_items as $index => $text) {
                if (!empty($text)) {
                    $newsData[] = [
                        'text' => $text,
                        'show_on_ticker' => in_array($index, $request->input('news_tickers', [])),
                    ];
                }
            }
        }
        $data['news'] = $newsData;

        // Process Slot Data
        if ($request->has('slot_data')) {
            $filteredSlots = array_filter($request->slot_data, fn($status) => $status !== 'available');
            $data['slot_data'] = $filteredSlots;
        }

        // Handle Image Upload
        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images/temples'), $imageName);
            $data['image'] = 'images/temples/' . $imageName;
        }

        Temple::create($data);

        return redirect()->route('admin.temples.index')->with('success', 'Temple created successfully.');
    }

    public function edit(Temple $temple)
    {
        $adminCalendars = $this->generateAdminCalendarData($temple);
        return view('admin.temples.edit', compact('temple', 'adminCalendars'));
    }

    /**
     * Update the specified temple in storage.
     */
    public function update(Request $request, Temple $temple)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        // 1. Update the simple text fields directly on the model
        $temple->fill($request->only(['name', 'location', 'description', 'about', 'online_services', 'social_services']));

        // 2. Process and update the News array
        $newsData = [];
        if ($request->has('news_items')) {
            foreach ($request->news_items as $index => $text) {
                if (!empty($text)) {
                    $newsData[] = [
                        'text' => $text,
                        'show_on_ticker' => in_array($index, $request->input('news_tickers', [])),
                    ];
                }
            }
        }
        $temple->news = $newsData;

        // 3. Process and update the Slot Booking data
        if ($request->has('slot_data')) {
            $filteredSlots = array_filter($request->slot_data, fn($status) => $status !== 'available');
            $temple->slot_data = $filteredSlots;
        } else {
            $temple->slot_data = null;
        }

        // 4. Handle the Image Upload
        if ($request->hasFile('image')) {
            if ($temple->image && file_exists(public_path($temple->image))) {
                unlink(public_path($temple->image));
            }
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images/temples'), $imageName);
            $temple->image = 'images/temples/' . $imageName;
        }

        // 5. Save all the changes to the database
        $temple->save();

        return redirect()->route('admin.temples.index')->with('success', 'Temple updated successfully.');
    }

    public function destroy(Temple $temple)
    {
        if ($temple->image && file_exists(public_path($temple->image))) {
            unlink(public_path($temple->image));
        }
        $temple->delete();
        return redirect()->route('admin.temples.index')->with('success', 'Temple deleted successfully.');
    }

    private function generateAdminCalendarData(Temple $temple)
    {
        $calendars = [];
        $currentDate = Carbon::now()->startOfMonth();
        $slotData = $temple->slot_data ?? [];

        for ($i = 0; $i < 4; $i++) {
            $monthName = $currentDate->format('F Y');
            $daysInMonth = $currentDate->daysInMonth;
            $days = [];

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = $currentDate->copy()->setDay($day);
                $dateString = $date->toDateString();
                $status = 'available';

                if (isset($slotData[$dateString])) {
                    $status = $slotData[$dateString];
                }

                $days[] = [
                    'day_number' => $day,
                    'date' => $dateString,
                    'status' => $status,
                ];
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
