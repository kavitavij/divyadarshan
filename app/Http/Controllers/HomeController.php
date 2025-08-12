<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Temple; // <-- Add this line

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Temple::query();

        // Handle search functionality
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
        }

        $temples = $query->latest()->paginate(8);
        $firstTemple = Temple::first();

        return view('home.index', compact('temples', 'firstTemple'));
    }
}