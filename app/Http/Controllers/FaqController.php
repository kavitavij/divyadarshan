<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'question' => 'required|string|min:10',
        ]);

        Faq::create($validated);

        return redirect()->route('about')
            ->with('success', 'Thank you for your question! We will review it and publish an answer shortly.')
            ->with('open_faq_modal', true);
    }
}
