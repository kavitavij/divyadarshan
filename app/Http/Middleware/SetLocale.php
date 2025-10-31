<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // Priority: session -> cookie -> Accept-Language header (fallback to app config)
        if (session()->has('locale')) {
            App::setLocale(session('locale'));
        } elseif ($request->cookie('locale')) {
            App::setLocale($request->cookie('locale'));
        }

        return $next($request);
    }
}
