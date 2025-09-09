<?php

namespace App\Http\Controllers\TempleManager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
class TempleController extends Controller
{
    /**
     * Show the form for editing the temple.
     */
    public function edit()
    {
        $temple = Auth::user()->temple;
        if (!$temple) {
            return redirect()->route('temple-manager.dashboard')->with('error', 'You are not assigned to a temple.');
        }
        return view('temple-manager.temple.edit', compact('temple'));
    }

    /**
     * Update the temple's information.
     */
    public function update(Request $request)
    {
        $temple = Auth::user()->temple;

        if (!$temple) {
            return redirect()->back()->with('error', 'You are not assigned to a temple.');
        }

        // ✅ FIXED: Add all your form fields to the validation rules
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'about' => 'nullable|string',
            'online_services' => 'nullable|string',
            'social_services' => 'nullable|string',
        ]);

        // Prepare the data for the update
        $updateData = $request->only([
            'name',
            'location',
            'description',
            'about',
            'online_services',
            'social_services'
        ]);

        // ✅ FIXED: Add proper image handling logic
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($temple->image) {
                Storage::disk('public')->delete($temple->image);
            }
            // Store the new image and get its path
            $path = $request->file('image')->store('temples', 'public');
            // Add the new image path to our data
            $updateData['image'] = $path;
        }

        // Update the temple with all the new data
        $temple->update($updateData);

        return redirect()->route('temple-manager.dashboard')->with('success', 'Temple details updated successfully.');
    }
}

