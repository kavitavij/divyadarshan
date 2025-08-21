<?php

namespace App\Http\Controllers\TempleManager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
   public function index()
{
    // $temple = Temple::where('manager_id', Auth::id())->first();
    return view('temple-manager.dashboard', compact('temple'));
}
}
