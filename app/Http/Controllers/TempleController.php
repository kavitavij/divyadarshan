<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Temple;

class TempleController extends Controller
{
    // Show all temples
    public function index()
    {
        $temples = Temple::all();
        return view('temples.index', compact('temples'));
    }

    // Show one temple
    public function show($id)
    {
        $temple = Temple::findOrFail($id);
        return view('temples.show', compact('temple'));
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
