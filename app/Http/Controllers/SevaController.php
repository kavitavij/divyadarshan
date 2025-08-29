<?php

namespace App\Http\Controllers;

use App\Models\Seva; // 1. Import the Seva model
use Illuminate\Http\Request;

class SevaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 2. Fetch all sevas from the database
        $sevas = Seva::all();

        // 3. Return the view and pass the sevas data to it
        return view('info.sevas', compact('sevas'));
    }
}
