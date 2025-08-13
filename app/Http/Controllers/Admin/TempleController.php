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
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except(['_token', 'news_items', 'news_tickers', 'slot_data', 'image']);

        // Handle News Items
        if ($request->has('news_items')) {
            $newsData = [];
            foreach ($request->news_items as $index => $text) {
                if (!empty($text)) {
                    $newsData[] = ['text' => $text, 'show_on_ticker' => in_array($index, $request->input('news_tickers', []))];
                }
            }
            $data['news'] = $newsData;
        }

        // Handle Slot Data
        if ($request->has('slot_data')) {
            $data['slot_data'] = array_filter($request->slot_data, fn($status) => $status !== 'not_available');
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
        $startDate = Carbon::today();
        $adminCalendars = [];
        $savedSlots = $temple->slot_data ?? [];

        for ($i = 0; $i < 4; $i++) {
            $month = $startDate->copy()->addMonths($i);
            $daysInMonth = [];
            for ($day = 1; $day <= $month->daysInMonth; $day++) {
                $currentDate = $month->copy()->setDay($day);
                $dateString = $currentDate->toDateString();
                $daysInMonth[] = [
                    'date' => $dateString,
                    'day_number' => $day,
                    'status' => $savedSlots[$dateString] ?? 'not_available',
                ];
            }
            $adminCalendars[] = [
                'month_name' => $month->format('F Y'),
                'days' => $daysInMonth,
            ];
        }

        return view('admin.temples.edit', compact('temple', 'adminCalendars'));
    }

    // In app/Http/Controllers/Admin/TempleController.php

    public function update(Request $request, Temple $temple)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        // 1. Get all the simple text-based fields from the form.
        $data = $request->only(['name', 'location', 'description', 'about', 'online_services', 'social_services']);

        // 2. Process the News Items array.
        if ($request->has('news_items')) {
            $newsData = [];
            foreach ($request->news_items as $index => $text) {
                if (!empty($text)) {
                    $newsData[] = ['text' => $text, 'show_on_ticker' => in_array($index, $request->input('news_tickers', []))];
                }
            }
            $data['news'] = $newsData;
        }

        // 3. Process the Slot Booking data.
        if ($request->has('slot_data')) {
            $filteredSlots = array_filter($request->slot_data, fn($status) => $status !== 'not_available');
            $data['slot_data'] = $filteredSlots;
        } else {
            $data['slot_data'] = null;
        }

        // 4. Handle the Image Upload.
        if ($request->hasFile('image')) {
            if ($temple->image && file_exists(public_path($temple->image))) {
                unlink(public_path($temple->image));
            }
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images/temples'), $imageName);
            $data['image'] = 'images/temples/' . $imageName;
        }

        // 5. Update the temple with the complete, merged data array.
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
}