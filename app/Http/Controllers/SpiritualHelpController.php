<?php

namespace App\Http\Controllers;

use App\Models\SpiritualHelpRequest;
use App\Models\Temple;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SpiritualFormMail;

class SpiritualHelpController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_info' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'query_type' => 'required|string',
            'temple_id' => 'nullable|exists:temples,id',
            'preferred_time' => 'required|string',
            'message' => 'required|string',
        ]);

        SpiritualHelpRequest::create($validated);
        $templeName = 'Not specified / General Inquiry';
        if (!empty($validated['temple_id'])) {
            $temple = Temple::find($validated['temple_id']);
            if ($temple) {
                $templeName = $temple->name;
            } else {
                $templeName = 'Temple ID not found in database';
            }
        }

        $orderedMailData = [
            'Name' => $validated['name'],
            'Contact Info' => $validated['contact_info'],
            'City' => $validated['city'],
            'Query Type' => $validated['query_type'],
            'Temple' => $templeName,
            'Preferred Time' => $validated['preferred_time'],
            'Message' => $validated['message'],
        ];
        $adminEmail = 'truckares@gmail.com';
        Mail::to($adminEmail)->send(new SpiritualFormMail($orderedMailData));
        return redirect()->back()->with('success', 'Your request has been submitted successfully! We will contact you soon.');
    }
}

