<?php

namespace App\Http\Controllers\TempleManager;

use App\Http\Controllers\Controller;
use App\Models\TempleImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    // Display the gallery management page
    public function index()
    {
        $temple = Auth::user()->temple;
        $images = $temple->galleryImages()->latest()->get();
        return view('temple-manager.gallery.index', compact('temple', 'images'));
    }

    // Store new images
    public function store(Request $request)
    {
        $request->validate([
            'images'   => 'required|array',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $temple = Auth::user()->temple;

        foreach ($request->file('images') as $file) {
            $path = $file->store('temple-galleries', 'public');
            $temple->galleryImages()->create(['path' => $path]);
        }

        return redirect()->back()->with('success', 'Images uploaded successfully!');
    }

    // Delete an image
    public function destroy(TempleImage $image)
    {
        // Security Check: Ensure the image belongs to the manager's temple
        if ($image->temple_id !== Auth::user()->temple->id) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        // Delete the file from storage
        Storage::disk('public')->delete($image->path);

        // Delete the record from the database
        $image->delete();

        return redirect()->back()->with('success', 'Image deleted successfully.');
    }
}
