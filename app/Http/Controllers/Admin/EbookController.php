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
        return view('admin.ebooks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:255',
            'cover_image' => 'nullable|image|max:2048',
            'ebook_file' => 'required|file|mimes:pdf',
        ]);

        $ebookFilePath = $request->file('ebook_file')->store('public/ebooks');

        $coverImagePath = null;
        if ($request->hasFile('cover_image')) {
            $coverImagePath = $request->file('cover_image')->store('public/ebook_covers');
        }

        Ebook::create([
            'title' => $request->title,
            'author' => $request->author,
            'cover_image_path' => $coverImagePath,
            'ebook_file_path' => $ebookFilePath,
        ]);

        return redirect()->route('admin.ebooks.index')->with('success', 'Ebook uploaded successfully.');
    }

    public function edit(Ebook $ebook)
    {
        return view('admin.ebooks.edit', compact('ebook'));
    }

    public function update(Request $request, Ebook $ebook)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:255',
            'cover_image' => 'nullable|image|max:2048',
            'ebook_file' => 'nullable|file|mimes:pdf',
        ]);

        $data = $request->only(['title', 'author']);

        if ($request->hasFile('cover_image')) {
            if ($ebook->cover_image_path) {
                Storage::delete($ebook->cover_image_path);
            }
            $data['cover_image_path'] = $request->file('cover_image')->store('public/ebook_covers');
        }

        if ($request->hasFile('ebook_file')) {
            if ($ebook->ebook_file_path) {
                Storage::delete($ebook->ebook_file_path);
            }
            $data['ebook_file_path'] = $request->file('ebook_file')->store('public/ebooks');
        }

        $ebook->update($data);

        return redirect()->route('admin.ebooks.index')->with('success', 'Ebook updated successfully.');
    }

    public function destroy(Ebook $ebook)
    {
        if ($ebook->cover_image_path) {
            Storage::delete($ebook->cover_image_path);
        }
        if ($ebook->ebook_file_path) {
            Storage::delete($ebook->ebook_file_path);
        }
        $ebook->delete();

        return redirect()->route('admin.ebooks.index')->with('success', 'Ebook deleted successfully.');
    }
}