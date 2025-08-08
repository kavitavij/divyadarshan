<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Temple;

class HomeController extends Controller
{
public function index(Request $request)
{
    $query = Temple::query();

    if ($search = $request->input('search')) {
        $query->where('name', 'like', "%$search%")
              ->orWhere('location', 'like', "%$search%");
    }

    $temples = $query->paginate(6);

    return view('home.index', compact('temples'));
}

}
