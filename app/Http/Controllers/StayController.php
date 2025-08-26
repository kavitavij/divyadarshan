<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\StayBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StayController extends Controller
{

    public function index()
    {
        // Temporarily remove the ->where() clause to see all hotels
        $hotels = Hotel::with('rooms')->paginate(10);

        return view('stays.index', compact('hotels'));
    }

    public function show(Hotel $hotel)
    {
        $hotel->load('rooms');
        return view('stays.show', compact('hotel'));
    }

        public function details(Room $room)
    {
        return view('stays.details', compact('room'));
    }
}
