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
     * NEW METHOD: Confirm the purchase after "payment" and attach the ebook to the user.
     */
    public function confirmPurchase(Request $request)
{
    $user = Auth::user();

    $cart = Session::get('cart', []);

    if (empty($cart)) {
        return redirect()->route('ebooks.index')->with('error', 'No items to purchase.');
    }

    foreach ($cart as $ebookId => $item) {
        // attach only if not already owned
        if (!$user->ebooks()->where('ebook_id', $ebookId)->exists()) {
            $user->ebooks()->attach($ebookId);
        }
    }

    // clear cart
    Session::forget('cart');

    return redirect()->route('profile.ebooks')->with('success', 'Purchase successful! Your eBooks are now available.');
}

    /**
     * Handle the download of a purchased ebook.
     */
    // ... in your read() method

    public function read(Ebook $ebook)
    {

        $path = Storage::disk('private')->path($ebook->ebook_file_path);

        if (!Storage::disk('private')->exists($ebook->ebook_file_path)) {
            abort(404, 'File not found.');
        }

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($path) . '"'
        ]);
    }
}
