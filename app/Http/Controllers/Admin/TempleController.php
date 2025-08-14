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
        return view('admin.temples.create');
    }

    public function store(Request $request)
    {
        // Your existing store logic...
        // This method is for creating new temples and is likely correct.
    }

    public function edit(Temple $temple)
    {
        $adminCalendars = $this->generateAdminCalendarData($temple);
        return view('admin.temples.edit', compact('temple', 'adminCalendars'));
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

    /**
     * THE FIX: This update method is rewritten to be more robust.
     */
    public function update(Request $request, Temple $temple)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        // 1. Update the simple text fields directly on the model
        $temple->name = $request->input('name');
        $temple->location = $request->input('location');
        $temple->description = $request->input('description');
        $temple->about = $request->input('about');
        $temple->online_services = $request->input('online_services');
        $temple->social_services = $request->input('social_services');

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
            // Filter out the default 'available' status to keep the database clean
            $filteredSlots = array_filter($request->slot_data, fn($status) => $status !== 'available');
            $temple->slot_data = $filteredSlots;
        } else {
            $temple->slot_data = null;
        }

        // 4. Handle the Image Upload
        if ($request->hasFile('image')) {
            // Optional: Delete the old image
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
        // ... your destroy logic ...
    }
}
