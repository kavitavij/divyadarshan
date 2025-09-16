<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ComplaintMail;

class ComplaintController extends Controller
{
    public function index()
    {
        return view('pages.complaint');
    }

    public function store(Request $request)
    {
        // 1. Validate all the incoming form data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'issue_type' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        Complaint::create($validatedData);

        $adminEmail = 'truckares@gmail.com';
        Mail::to($adminEmail)->send(new ComplaintMail($validatedData));

        return response()->json([
            'success' => true,
            'message' => 'Thank you! Your complaint has been submitted successfully.',
            'redirect_url' => route('home') // Provide the URL for the "Back to Home" button
        ]);
    }
}
