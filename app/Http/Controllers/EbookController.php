<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EbookController extends Controller
{
    public function index(Request $request)
    {
        $selectedLang = $request->get('lang', 'All');

        $languages = ['All', 'Hindi', 'English', 'Punjabi'];

        // Sample E-books data (this can come from DB later)
        $books = [
            ['title' => 'Tirupati Temple Guide', 'lang' => 'English', 'cover' => 'temple1_1.jpg', 'file' => 'tirupati.pdf'],
            ['title' => 'वृन्दावन मंदिर', 'lang' => 'Hindi', 'cover' => 'book2.jpg', 'file' => 'vrindavan.pdf'],
            ['title' => 'Golden Temple History', 'lang' => 'Punjabi', 'cover' => 'book3.jpg', 'file' => 'golden.pdf'],
            ['title' => 'ABBC', 'lang' => 'ABC', 'cover' => 'book3.jpg', 'file' => 'golden.pdf'],
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              
        ];

        // Filter by language
        if ($selectedLang !== 'All') {
            $books = array_filter($books, fn($b) => strtolower($b['lang']) === strtolower($selectedLang));
        }

        return view('ebooks.index', compact('books', 'languages', 'selectedLang'));
    }
}
