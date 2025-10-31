<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\ContactSubmission; // THE FIX: Add this line to import the model

class GeneralInfoController extends Controller
{
    /**
     * Display the FAQs page.
     */
    public function faq(): View
    {
        return view('info.faq');
    }

    /**
     * Display the Sevas page.
     */
    public function sevas(): View
    {
        return view('info.sevas');
    }

    /**
     * Display the Dress Code page.
     */
    public function dressCode(): View
    {
        return view('info.dress-code');
    }

    /**
     * Display the Contact Us page.
     */


    /**
     * Handle the submission of the Contact Us form.
     */
    public function handleContactForm(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        // This line will now work correctly
        ContactSubmission::create($validatedData);

        return back()->with('success', 'Thank you for your message! We will get back to you shortly.');
    }
}
