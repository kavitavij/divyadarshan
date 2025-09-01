<?php

namespace App\Http\Controllers\TempleManager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        // Check if the update is coming from the T&C modal
        if ($request->input('update_source') === 'terms_modal') {
            $validatedData = $request->validate([
                'terms_and_conditions'   => 'nullable|array',
                'terms_and_conditions.*' => 'nullable|string|max:1000',
            ]);

            $terms = $validatedData['terms_and_conditions'] ?? [];
            $temple->terms_and_conditions = array_filter($terms);
            $temple->save();

            return redirect()->back()->with('success', 'Terms & Conditions updated successfully.');
        }

        // --- Otherwise, handle the FULL edit form ---
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            // Handle image upload logic here
        }

        $temple->update($validatedData);

        return redirect()->route('temple-manager.dashboard')->with('success', 'Temple details updated successfully.');
    }
}
