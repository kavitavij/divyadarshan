<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Temple;
use App\Models\Ebook;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'templeCount' => Temple::count(),
            'ebookCount'  => Ebook::count(),
            'userCount'   => User::count()
        ]);
    }
}
