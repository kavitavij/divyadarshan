<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Temple; // 1. Import the Temple model
use Illuminate\Http\Request;

class DonationController extends Controller
{
    /**
     * Display a listing of the resource.
     * This method fetches all donations with their related user and temple,
     * orders them by the newest first, and displays them in a paginated list.
     * It also allows searching and filtering for donations.
     */
    public function index(Request $request)
    {
        $query = Donation::with(['user', 'temple'])->latest();

        // 2. Handle the main search bar
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('user', function ($userQuery) use ($searchTerm) {
                      $userQuery->where('name', 'like', '%' . $searchTerm . '%')
                                ->orWhere('email', 'like', '%' . $searchTerm . '%');
                  })
                  ->orWhereHas('temple', function ($templeQuery) use ($searchTerm) {
                      $templeQuery->where('name', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        // 3. Handle advanced filters from the dropdown
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        if ($request->filled('temple')) {
            $query->where('temple_id', $request->temple);
        }
        if ($request->filled('min_amount')) {
            $query->where('amount', '>=', $request->min_amount);
        }
        if ($request->filled('max_amount')) {
            $query->where('amount', '<=', $request->max_amount);
        }

        $donations = $query->paginate(15)->appends($request->query());

        // 4. Fetch all temples to pass to the filter dropdown
        $temples = Temple::orderBy('name')->get();

        return view('admin.donations.index', compact('donations', 'temples'));
    }

    /**
     * Display the specified resource.
     * This method shows the detailed view for a single donation.
     * It also loads the user and temple relationships to be displayed in the view.
     *
     * @param  \App\Models\Donation  $donation
     * @return \Illuminate\View\View
     */
    public function show(Donation $donation)
    {
        // Eager load relationships for the details view
        $donation->load(['user', 'temple']);
        return view('admin.donations.show', compact('donation'));
    }
}

