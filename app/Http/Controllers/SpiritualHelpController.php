<?php

namespace App\Http\Controllers;

use App\Models\SpiritualHelpRequest;
use App\Models\Temple;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SpiritualFormMail;
use Illuminate\Support\Facades\Validator;

class SpiritualHelpController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'contact_info' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'query_type' => 'required|string',
            'temple_id' => 'nullable|exists:temples,id',
            'preferred_time' => 'required|string',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('show_spiritual_help_modal', true);
        }
        $validated = $validator->validated();
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

    $adminEmail = env('ADMIN_EMAIL');
    Mail::to($adminEmail)->send(new SpiritualFormMail($orderedMailData));

        return redirect()->back()->with('status', 'Your request has been submitted successfully! We will contact you soon.');
    }
}
