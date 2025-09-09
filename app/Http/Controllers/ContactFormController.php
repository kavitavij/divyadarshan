<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactSubmission;

class ContactFormController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|min:10',
        ]);

        ContactSubmission::create($validated);

        // Redirect back with a flag to re-open the modal
        return redirect()->back()
            ->with('success', 'Thank you for your message! We will get back to you shortly.')
            ->with('open_contact_modal', true);
    }
}
