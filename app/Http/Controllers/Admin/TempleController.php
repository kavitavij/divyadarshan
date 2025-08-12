<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Temple;
use Illuminate\Http\Request;

class TempleController extends Controller
{
    public function index()
    {
        $temples = Temple::all();
        return view('admin.temples.index', compact('temples'));
    }

    public function create()
    {
        return view('admin.temples.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'location' => 'required',
        ]);

        Temple::create($request->all());

        return redirect()->route('admin.temples.index')
                         ->with('success', 'Temple created successfully.');
    }

    public function edit(Temple $temple)
    {
        return view('admin.temples.edit', compact('temple'));
    }

    public function update(Request $request, Temple $temple)
    {
        $request->validate([
            'name' => 'required',
            'location' => 'required',
        ]);

        $temple->update($request->all());

        return redirect()->route('admin.temples.index')
                         ->with('success', 'Temple updated successfully.');
    }

    public function destroy(Temple $temple)
    {
        $temple->delete();

        return redirect()->route('admin.temples.index')
                         ->with('success', 'Temple deleted successfully.');
    }
}