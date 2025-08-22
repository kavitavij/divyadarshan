<?php

namespace App\Http\Controllers\TempleManager;

use App\Http\Controllers\Controller;
use App\Models\Seva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SevaController extends Controller
{
    private function getManagerTemple()
    {
        return Auth::user()->temple;
    }

    public function index()
    {
        $temple = $this->getManagerTemple();
        if (!$temple) {
            return redirect()->route('temple-manager.dashboard')->with('error', 'You are not assigned to a temple.');
        }
        $sevas = $temple->sevas()->latest()->paginate(10);
        return view('temple-manager.sevas.index', compact('temple', 'sevas'));
    }

    public function create()
    {
        $temple = $this->getManagerTemple();
        return view('temple-manager.sevas.create', compact('temple'));
    }

    public function store(Request $request)
    {
        $temple = $this->getManagerTemple();
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'type' => 'required|string',
        ]);

        $temple->sevas()->create($request->all());
        return redirect()->route('temple-manager.sevas.index')->with('success', 'Seva created successfully.');
    }

    public function edit(Seva $seva)
    {
        if ($seva->temple_id !== $this->getManagerTemple()->id) {
            abort(403);
        }
        return view('temple-manager.sevas.edit', compact('seva'));
    }

    public function update(Request $request, Seva $seva)
    {
        if ($seva->temple_id !== $this->getManagerTemple()->id) {
            abort(403);
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'type' => 'required|string',
        ]);

        $seva->update($request->all());
        return redirect()->route('temple-manager.sevas.index')->with('success', 'Seva updated successfully.');
    }

    public function destroy(Seva $seva)
    {
        if ($seva->temple_id !== $this->getManagerTemple()->id) {
            abort(403);
        }
        $seva->delete();
        return redirect()->route('temple-manager.sevas.index')->with('success', 'Seva deleted successfully.');
    }
}
