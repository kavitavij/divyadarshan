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
        if (Session::has('locale') && in_array(Session::get('locale'), ['en', 'hi'])) {
            App::setLocale(Session::get('locale'));
        }

        return $next($request);
    }
}
