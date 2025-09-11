<?php

namespace App\Http\Controllers\HotelManager;

use App\Http\Controllers\Controller;
use App\Models\HotelImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HotelImageController extends Controller
{
    /**
     * Display the hotel's image gallery management page.
     */
    public function index()
    {
        $hotel = Auth::user()->hotel;
        $hotel->load('images'); // Eager load the images
        return view('hotel-manager.gallery.index', compact('hotel'));
    }

    /**
     * Store a newly uploaded gallery image.
     */
    public function store(Request $request)
    {
        $request->validate([
            'images' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048' // Validate each image in the array
        ]);

        $hotel = Auth::user()->hotel;

        foreach ($request->file('images') as $imagefile) {
            $path = $imagefile->store('hotel_gallery', 'public');
            $hotel->images()->create(['path' => $path]);
        }

        return back()->with('success', 'Images uploaded successfully!');
    }

    /**
     * Remove the specified image from storage.
     */
    public function destroy(HotelImage $image)
{
    // ✅ This single line replaces your old 'if' check.
    // It automatically finds the HotelImagePolicy and runs the 'delete' method.
    // If it returns false, a 403 error is thrown automatically.
    $this->authorize('delete', $image);

    // Delete the file from storage
    Storage::disk('public')->delete($image->path);

    // Delete the record from the database
    $image->delete();

    return back()->with('success', 'Image deleted successfully.');
}
}
