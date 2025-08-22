<?php

namespace App\Http\Controllers\TempleManager;

use App\Http\Controllers\Controller;
use App\Models\Temple;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TempleController extends Controller
{
    /**
     * Show the form for editing the temple managed by the current user.
     */
    public function edit()
    {
        $manager = Auth::user();
        $temple = $manager->temple;

        if (!$temple) {
            return redirect()->route('temple-manager.dashboard')->with('error', 'You are not assigned to a temple.');
        }

        return view('temple-manager.temple.edit', compact('temple'));
    }

    /**
     * Update the temple's information in storage.
     */
    public function update(Request $request)
    {
        $manager = Auth::user();
        $temple = $manager->temple;

        if (!$temple) {
            return redirect()->route('temple-manager.dashboard')->with('error', 'You are not assigned to a temple.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'location', 'description', 'about', 'online_services', 'social_services']);

        if ($request->hasFile('image')) {
            if ($temple->image && file_exists(public_path($temple->image))) {
                unlink(public_path($temple->image));
            }
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images/temples'), $imageName);
            $data['image'] = 'images/temples/' . $imageName;
        }

        $temple->update($data);

        return redirect()->route('temple-manager.dashboard')->with('success', 'Temple details updated successfully.');
    }
}
