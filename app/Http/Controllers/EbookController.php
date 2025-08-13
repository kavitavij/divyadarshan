<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use Illuminate\Http\Request;

class EbookController extends Controller
{
    public function index(Request $request)
    {
        // Define the available languages for the filter menu
        $languages = ['English', 'Hindi', 'Tamil', 'Telugu'];

        // Start building the query to get ebooks
        $query = Ebook::query();

        // --- Filter by Language ---
        if ($request->filled('language')) {
            $query->where('language', $request->language);
        }

        // --- Filter by Type (Paid/Free) ---
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Get the filtered ebooks, newest first, and paginate them
        $ebooks = $query->latest()->paginate(12)->withQueryString();

        // Send all the data to the view
        return view('ebooks.index', [
            'ebooks' => $ebooks,
            'languages' => $languages,
            'selectedLanguage' => $request->language, 
            'selectedType' => $request->type,         
        ]);
    }
}