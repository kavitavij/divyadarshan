<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Temple;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;

class TempleController extends Controller
{
    public function index()
    {
        $temples = Temple::latest()->paginate(10);
        return view('admin.temples.index', compact('temples'));
    }

    public function create()
    {
        $managers = User::where('role', 'temple_manager','admin')->get();
        return view('admin.temples.create', compact('managers'));
    }

        public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'image' => 'required|image|max:2048',
            'darshan_charge' => 'nullable|numeric|min:0',
            'offered_services' => 'nullable|array' // Validation for services
        ]);

        $data = $request->only(['name', 'location','darshan_charge', 'description', 'about', 'online_services', 'social_services']);

        // Handle Offered Services
        $data['offered_services'] = $request->input('offered_services', []);

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
    $managers = User::whereIn('role', ['temple_manager', 'admin'])->get();

    // Generate calendar data
    $adminCalendars = $this->generateAdminCalendarData($temple);

    return view('admin.temples.edit', compact('temple', 'managers', 'adminCalendars'));
}


    public function update(Request $request, Temple $temple)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'location' => 'required|string|max:255',
        'image' => 'nullable|image|max:2048',
        'darshan_charge' => 'nullable|numeric|min:0',
        'about' => 'nullable|string',
        'online_services' => 'nullable|string',
        'social_services' => 'nullable|string',
        'offered_services' => 'nullable|array',
        'slot_data' => 'nullable|array',
        'news_items' => 'nullable|array',
        'news_tickers' => 'nullable|array',
    ]);

    $data = $request->only([
        'name','location','darshan_charge','description','about','online_services','social_services'
    ]);

    // Offered Services
    $data['offered_services'] = $request->input('offered_services', []);

    // Slot Booking
    $data['slot_data'] = $request->input('slot_data', []);

    // News Items
    $newsItemsInput = $request->input('news_items', []);
    $newsTickersInput = $request->input('news_tickers', []);
    $news = [];
    foreach ($newsItemsInput as $index => $text) {
        if ($text) {
            $news[] = [
                'text' => $text,
                'show_on_ticker' => in_array($index, $newsTickersInput)
            ];
        }
    }
    $data['news'] = $news;

    // Handle Image Upload
    if ($request->hasFile('image')) {
        if ($temple->image && file_exists(public_path($temple->image))) {
            unlink(public_path($temple->image));
        }
        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('images/temples'), $imageName);
        $data['image'] = 'images/temples/' . $imageName;
    }

    $temple->update($data);

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
