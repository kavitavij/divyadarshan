<?php
namespace App\Http\Controllers;
use App\Models\SpiritualHelpRequest;
use Illuminate\Http\Request;

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

        return redirect()->back()->with('success', 'Your request has been submitted successfully! We will contact you soon.');
    }
}
