<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;

class StayController extends Controller
{
    /**
     * Display a listing of all available hotels.
     */
    public function index()
    {
        $hotels = Hotel::with('temple')->latest()->paginate(12);
        return view('stays.index', compact('hotels'));
    }

    /**
     * Display the specified hotel and its available rooms.
     */
    public function show(Hotel $hotel)
    {
        // Eager load the rooms to improve performance
        $hotel->load('rooms');
        return view('stays.show', compact('hotel'));
    }
}
