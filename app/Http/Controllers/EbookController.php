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
        public function purchase(Request $request, Ebook $ebook)
        {
            $user = Auth::user();

            // Check if the user already owns this ebook
            if ($user->ebooks()->where('ebook_id', $ebook->id)->exists()) {
                return redirect()->route('profile.ebooks')->with('info', 'You already own this eBook.');
            }

            // This is where a real payment gateway would be integrated.
            // For now, we'll just attach the book to the user directly.
            $user->ebooks()->attach($ebook->id);

            return redirect()->route('profile.ebooks')->with('success', 'eBook purchased successfully! It is now available in your account.');
        }

        /**
         * NEW METHOD: Handle the download of a purchased ebook.
         */
        public function download(Ebook $ebook)
        {
            $user = Auth::user();

            // Security Check: Ensure the user actually owns the ebook before allowing a download.
            if (!$user->ebooks()->where('ebook_id', $ebook->id)->exists()) {
                abort(403, 'Unauthorized action.');
            }

            // Make sure your Ebook model has a 'file_path' column in the database
            // that stores the path to the file in your storage (e.g., 'ebooks/my-book.pdf')
            $filePath = storage_path('app/' . $ebook->file_path);

            if (!file_exists($filePath)) {
                abort(404, 'File not found.');
            }

            return response()->download($filePath);
        }    
}