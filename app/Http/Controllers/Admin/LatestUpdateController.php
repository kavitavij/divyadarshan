<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LatestUpdate;
use Illuminate\Http\Request;

class LatestUpdateController extends Controller
{
    public function index()
    {
        $updates = LatestUpdate::latest()->get();
        return view('admin.latest_updates.index', compact('updates'));
    }

    public function create()
    {
        return view('admin.latest_updates.create');
    }

    public function store(Request $request)
    {
        $request->validate(['message' => 'required|string|max:255']);
        LatestUpdate::create($request->all());
        return redirect()->route('admin.latest_updates.index')->with('success', 'Update created successfully.');
    }

    public function edit(LatestUpdate $latestUpdate)
    {
        return view('admin.latest_updates.edit', compact('latestUpdate'));
    }

    public function update(Request $request, LatestUpdate $latestUpdate)
    {
        $request->validate(['message' => 'required|string|max:255']);
        $latestUpdate->update($request->all());
        return redirect()->route('admin.latest_updates.index')->with('success', 'Update updated successfully.');
    }

    public function destroy(LatestUpdate $latestUpdate)
    {
        $latestUpdate->delete();
        return redirect()->route('admin.latest_updates.index')->with('success', 'Update deleted successfully.');
    }
}