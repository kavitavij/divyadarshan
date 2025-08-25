<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switch($locale)
    {
        if (in_array($locale, ['en', 'hi'])) {
            Session::put('locale', $locale);
        }
        return redirect()->back();
    }
}
