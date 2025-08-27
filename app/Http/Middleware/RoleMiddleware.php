<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // First, check if the user is authenticated.
        if (!Auth::check()) {
            // If not logged in, redirect to the login page.
            return redirect('login');
        }

        // Get the authenticated user.
        $user = Auth::user();

        // Loop through the roles required for the route (e.g., 'admin', 'temple_manager').
        foreach ($roles as $role) {
            // Check if the user's role column matches the required role.
            if ($user->role === $role) {
                // If a match is found, allow the request to proceed.
                return $next($request);
            }
        }

        // If no matching role was found, block access.
        abort(403, 'Unauthorized action.');
    }
}
