<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

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
    public function contact(): View
    {
        return view('info.contact');
    }

    /**
     * Handle the submission of the Contact Us form.
     */
    public function handleContactForm(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        // In a real application, you would send an email or save this to the database.
        // For now, we'll just redirect back with a success message.
        return back()->with('success', 'Thank you for your message! We will get back to you shortly.');
    }
}
