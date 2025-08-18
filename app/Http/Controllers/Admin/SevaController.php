<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seva;
use App\Models\Temple;
use Illuminate\Http\Request;

class SevaController extends Controller
{
    /**
     * Display a listing of the sevas for a specific temple.
     */
    public function index(Temple $temple)
    {
        $sevas = $temple->sevas()->latest()->paginate(10);
        return view('admin.sevas.index', compact('temple', 'sevas'));
    }

    /**
     * Show the form for creating a new seva for a specific temple.
     */
    public function create(Temple $temple)
    {
        return view('admin.sevas.create', compact('temple'));
    }

    /**
     * Store a newly created seva in storage.
     */
    public function store(Request $request, Temple $temple)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'type' => 'required|string',
        ]);

        $temple->sevas()->create($request->all());

        return redirect()->route('admin.temples.sevas.index', $temple)->with('success', 'Seva created successfully.');
    }

    /**
     * NEW: Show the form for editing the specified seva.
     */
    public function edit(Seva $seva)
    {
        // The temple is loaded via the seva's relationship
        return view('admin.sevas.edit', compact('seva'));
    }

    /**
     * NEW: Update the specified seva in storage.
     */
    public function update(Request $request, Seva $seva)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'type' => 'required|string',
        ]);

        $seva->update($request->all());

        return redirect()->route('admin.temples.sevas.index', $seva->temple_id)->with('success', 'Seva updated successfully.');
    }

    // You can add a destroy method here later
}
