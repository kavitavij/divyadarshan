<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ebook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EbookController extends Controller
{
    public function index()
    {
        $ebooks = Ebook::latest()->paginate(10);
        return view('admin.ebooks.index', compact('ebooks'));
    }

    public function create()
    {   
        $languages = config('languages.available');
        return view('admin.ebooks.create', compact('languages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:255',
            'language' => 'required|string',
            'type' => 'required|in:free,paid',
            'price' => 'required_if:type,paid|nullable|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100', // Validation for discount
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'ebook_file' => 'required|file|mimes:pdf|max:10240', // PDF up to 10MB
        ]);
        $data = $request->only(['title', 'author', 'language', 'type', 'price', 'discount_percentage']);
        if ($data['type'] === 'free') $data['price'] = null;

        if ($request->hasFile('ebook_file')) $data['ebook_file_path'] = $request->file('ebook_file')->store('ebooks', 'public');
        if ($request->hasFile('cover_image')) $data['cover_image_path'] = $request->file('cover_image')->store('ebook_covers', 'public');

        Ebook::create($data);
        return redirect()->route('admin.ebooks.index')->with('success', 'Ebook uploaded successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     * THIS METHOD CONTAINS THE FIX.
     */
    public function edit(Ebook $ebook)
    {
        // It's crucial that this line exists to send the languages to the view.
        $languages = config('languages.available');
        return view('admin.ebooks.edit', compact('ebook', 'languages'));
    }

    public function update(Request $request, Ebook $ebook)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:255',
            'language' => 'required|string',
            'type' => 'required|in:free,paid',
            'price' => 'required_if:type,paid|nullable|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100', // Validation for discount
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'ebook_file' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $data = $request->only(['title', 'author', 'language', 'type', 'price', 'discount_percentage']);

        if ($data['type'] === 'free') $data['price'] = null;

        if ($request->hasFile('ebook_file')) {
            if ($ebook->ebook_file_path) Storage::disk('public')->delete($ebook->ebook_file_path);
            $data['ebook_file_path'] = $request->file('ebook_file')->store('ebooks', 'public');
        }
        if ($request->hasFile('cover_image')) {
            if ($ebook->cover_image_path) Storage::disk('public')->delete($ebook->cover_image_path);
            $data['cover_image_path'] = $request->file('cover_image')->store('ebook_covers', 'public');
        }

        $ebook->update($data);
        return redirect()->route('admin.ebooks.index')->with('success', 'Ebook updated successfully.');
    }

    public function destroy(Ebook $ebook)
    {
        if ($ebook->cover_image_path) Storage::disk('public')->delete($ebook->cover_image_path);
        if ($ebook->ebook_file_path) Storage::disk('public')->delete($ebook->ebook_file_path);
        $ebook->delete();
        return redirect()->route('admin.ebooks.index')->with('success', 'Ebook deleted successfully.');
    }
}
