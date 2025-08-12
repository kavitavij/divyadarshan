<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Temple;
use Carbon\Carbon;

class TempleController extends Controller
{
    /**
     * Display a listing of all temples for the public.
     */
    public function index(Request $request)
    {
        $query = Temple::query();

        // Handle search functionality on the public temple listing page
        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
        }

        $temples = $query->latest()->paginate(12);

        return view('temples.index', compact('temples'));
    }

    /**
     * Display a single temple's details to the public.
     */
    public function show($id)
    {
        $temple = Temple::findOrFail($id);

        // --- Calendar Generation for Public View ---
        $startDate = Carbon::today();
        $calendars = [];
        $savedSlots = $temple->slot_data ?? []; // Get saved data, or empty array if null

        for ($i = 0; $i < 4; $i++) {
            $month = $startDate->copy()->addMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();
            $days = [];

            // Add blank days to ensure the first day of the month starts on the correct day of the week
            $blankDays = ($monthStart->dayOfWeek + 6) % 7;
            for ($b = 0; $b < $blankDays; $b++) {
                $days[] = null;
            }

            // Populate the days of the month
            for ($date = $monthStart->copy(); $date->lte($monthEnd); $date->addDay()) {
                $dateString = $date->toDateString();
                $days[] = [
                    'date' => $dateString,
                    'day' => $date->day,
                    // Get the status from your saved data, or default to 'not_available'
                    'status' => $savedSlots[$dateString] ?? 'not_available',
                ];
            }

            $calendars[] = [
                'month_name' => $month->format('F Y'),
                'days' => $days,
            ];
        }

        return view('temples.detail', compact('temple', 'calendars'));
    }

    /**
     * Allows a logged-in user to favorite a temple.
     */
    public function favorite($id)
    {
        // Find the temple first
        $temple = Temple::findOrFail($id);
        
        // Attach it to the logged-in user's favorites
        auth()->user()->favorites()->syncWithoutDetaching($temple->id);

        return back()->with('success', 'Temple bookmarked successfully!');
    }
}