<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TermsController extends Controller
{
    public function index()
    {
        // This method simply shows the terms view.
        return view('pages.terms');
    }
}