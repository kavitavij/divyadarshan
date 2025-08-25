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
        $temple = new Temple();
        $adminCalendars = $this->generateAdminCalendarData($temple);
        return view('admin.temples.create', compact('temple', 'adminCalendars'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'darshan_charge' => 'nullable|numeric|min:2',
        ]);

        $data = $request->only(['name', 'location','darshan_charge', 'description', 'about', 'online_services', 'social_services']);

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

    public function update(Request $request, Temple $temple)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'darshan_charge' => 'nullable|numeric|min:2',
        ]);

        $temple->fill($request->only(['name','darshan_charge', 'location', 'description', 'about', 'online_services', 'social_services']));

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

        if ($request->has('slot_data')) {
            $filteredSlots = array_filter($request->slot_data, fn($status) => $status !== 'available');
            $temple->slot_data = $filteredSlots;
        } else {
            $temple->slot_data = null;
        }

        if ($request->hasFile('image')) {
            if ($temple->image && file_exists(public_path($temple->image))) {
                unlink(public_path($temple->image));
            }
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images/temples'), $imageName);
            $temple->image = 'images/temples/' . $imageName;
        }

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

    /**
     * Display all Darshan bookings for a specific temple.
     */
    public function showDarshanBookings(Temple $temple)
    {
        $bookings = $temple->darshanBookings()->with('user')->latest()->paginate(20);
        return view('admin.temples.darshan_bookings', compact('temple', 'bookings'));
    }

    /**
     * Display all Seva bookings for a specific temple.
     */
    public function showSevaBookings(Temple $temple)
    {
        $bookings = $temple->sevaBookings()->with('user', 'seva')->latest()->paginate(20);
        return view('admin.temples.seva_bookings', compact('temple', 'bookings'));
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
    public function dashboard()
{
//     $temple = Temple::where('manager_id', Auth::id())->first(); // Adjust logic as needed
    return view('temple-manager.dashboard', compact('temple'));
}
}
