<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactSubmission;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;

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

        $adminEmail = 'truckares@gmail.com';
        Mail::to($adminEmail)->send(new ContactFormMail($validated));

        return redirect()->back()
            ->with('success', 'Thank you for your message! We will get back to you shortly.')
            ->with('open_contact_modal', true);
    }
}
