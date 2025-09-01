<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Temple;
use App\Exports\DonationsExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DonationController extends Controller
{
    /**
     * Display a paginated list of donations with search and filter capabilities.
     */
    public function index(Request $request)
    {
        // 1. Get the filtered query without executing it yet
        $query = $this->getFilteredDonationsQuery($request);

        // 2. Paginate the results for display on the page
        $donations = $query->paginate(15)->appends($request->query());

        // 3. Fetch all temples to populate the filter dropdown
        $temples = Temple::orderBy('name')->get();

        return view('admin.donations.index', compact('donations', 'temples'));
    }

    /**
     * Export filtered donations to an Excel file.
     */
    public function export(Request $request)
    {
        // 1. Get the exact same filtered query
        $query = $this->getFilteredDonationsQuery($request);

        // 2. Fetch all matching records without pagination
        $donationsToExport = $query->get();

        // 3. Generate a dynamic filename
        $fileName = 'donations-' . now()->format('Y-m-d-His') . '.xlsx';

        // 4. Trigger the download
        return Excel::download(new DonationsExport($donationsToExport), $fileName);
    }

    /**
     * A private helper method to build the filtered query.
     * This avoids code duplication between index() and export().
     */
    private function getFilteredDonationsQuery(Request $request)
    {
        $query = Donation::with(['user', 'temple'])->latest();

        // Handle the main search bar (name, email, temple name)
        if ($request->filled('search')) {
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

        // Handle advanced filters
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        if ($request->filled('temple_id')) {
            $query->where('temple_id', $request->temple_id);
        }
        if ($request->filled('min_amount')) {
            $query->where('amount', '>=', $request->min_amount);
        }
        if ($request->filled('max_amount')) {
            $query->where('amount', '<=', $request->max_amount);
        }

        return $query;
    }

    /**
     * Display the specified resource.
     */
    public function show(Donation $donation)
    {
        $donation->load(['user', 'temple']);
        return view('admin.donations.show', compact('donation'));
    }
}

