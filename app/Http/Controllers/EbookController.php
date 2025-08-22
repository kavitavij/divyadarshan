<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EbookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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

    /**
     * Handle the purchase of an ebook by redirecting to the payment page.
     */
    public function purchase(Request $request, Ebook $ebook)
    {
        $user = Auth::user();

        if ($user->ebooks()->where('ebook_id', $ebook->id)->exists()) {
            return redirect()->route('profile.ebooks')->with('info', 'You already own this eBook.');
        }

        // THE FIX: Redirect to the universal payment page
        return redirect()->route('payment.show', ['type' => 'ebook', 'id' => $ebook->id]);
    }

    /**
     * NEW METHOD: Confirm the purchase after "payment" and attach the ebook to the user.
     */
    public function confirmPurchase(Request $request)
    {
        $request->validate([
            'ebook_id' => 'required|exists:ebooks,id',
        ]);

        $user = Auth::user();
        $ebook = Ebook::findOrFail($request->ebook_id);

        // Attach the book to the user
        $user->ebooks()->attach($ebook->id);

        return redirect()->route('profile.ebooks')->with('success', 'eBook purchased successfully! It is now available in your account.');
    }

    /**
     * Handle the download of a purchased ebook.
     */
    public function download(Ebook $ebook)
    {
        $user = Auth::user();

        if (!$user->ebooks()->where('ebook_id', $ebook->id)->exists()) {
            abort(403, 'Unauthorized action.');
        }

        $filePath = storage_path('app/' . $ebook->file_path);

        if (!file_exists($filePath)) {
            abort(404, 'File not found.');
        }

        return response()->download($filePath);
    }
}
