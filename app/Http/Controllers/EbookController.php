<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use Illuminate\Http\Request;

class EbookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ebooks = Ebook::latest()->paginate(12); // Get the 12 newest ebooks
        return view('ebooks.index', compact('ebooks'));
    }
}