<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function index()
    {
        return view('pages.complaint');
    }
     public function store(Request $request)
    {
        // It's a good practice to validate the input
        $validatedData = $request->validate([
            // Add your form fields here for validation
            // Example: 'name' => 'required|string|max:255',
            // 'email' => 'required|email',
            // 'details' => 'required',
        ]);

        // Create the complaint using only validated data
        $complaint = Complaint::create($request->all()); // Or use $validatedData

        // Instead of redirecting, return a JSON response
        return response()->json([
            'success' => true,
            'message' => 'Thank you! Your complaint has been submitted.',
            'redirect_url' => route('home')
        ]);
    }
}