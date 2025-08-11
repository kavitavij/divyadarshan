<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Temple;
use Carbon\Carbon;

class TempleController extends Controller
{
    // Show all temples
   public function index(Request $request)
{
    $query = Temple::query();

    if ($search = $request->input('search')) {
        $query->where('name', 'like', "%{$search}%")
              ->orWhere('location', 'like', "%{$search}%");
    }

    $temples = $query->paginate(12);

    return view('temples.index', compact('temples'));
}

public function show($id)
{
    $temple = Temple::findOrFail($id);

    $startDate = Carbon::today();
    $calendars = [];

    for ($i = 0; $i < 4; $i++) {
        $monthStart = $startDate->copy()->addMonths($i)->startOfMonth();
        $monthEnd = $monthStart->copy()->endOfMonth();

        $days = [];

        // Fill blanks for days before month start (Monday is first day in W3Schools)
        $blankDays = ($monthStart->dayOfWeek + 6) % 7; // convert Sunday=0 to Sunday=6 for Monday-start calendar
        for ($b = 0; $b < $blankDays; $b++) {
            $days[] = null;
        }

        for ($date = $monthStart; $date->lte($monthEnd); $date->addDay()) {
            // Random example slot status; replace with real data fetching
            $rand = rand(1, 10);
            $status = $rand <= 6 ? 'available' : ($rand <= 8 ? 'full' : 'not_available');

            $days[] = [
                'date' => $date->toDateString(),
                'day' => $date->day,
                'status' => $status,
            ];
        }

        $calendars[] = [
            'month_name' => $monthStart->format('F Y'),
            'days' => $days,
        ];
    }

    return view('temples.detail', compact('temple', 'calendars'));
}


    // Store a new temple
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
        $request->file('image')->move(public_path('images/temples'), $imageName);

        Temple::create([
            'name' => $request->name,
            'location' => $request->location,
            'description' => $request->description,
            'image' => $imageName,
        ]);

        return redirect()->back()->with('success', 'Temple added successfully!');
    }

    // Bookmark a temple
    public function favorite($id)
    {
        auth()->user()->favorites()->attach($id);
        return back()->with('success', 'Temple bookmarked!');
    }

    // Show form to create a temple (optional)
    public function create()
    {
        return view('temples.create');
    }

    // Show form to edit a temple (optional)
    public function edit(Temple $temple)
    {
        return view('temples.edit', compact('temple'));
    }

    // Update a temple (optional)
    public function update(Request $request, Temple $temple)
    {
        // Add update logic here later if needed
    }

    // Delete a temple (optional)
    public function destroy(Temple $temple)
    {
        // Add delete logic here later if needed
    }
    
}