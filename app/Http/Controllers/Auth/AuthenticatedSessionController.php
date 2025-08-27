<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show login page
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate login fields
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Try to authenticate
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // --- CORRECTED REDIRECT LOGIC ---
            // Redirect by role, using the exact names from your database and routes file.
            switch ($user->role) {
                case 'admin':
                    // Assuming your admin route is named 'admin.dashboard'
                    return redirect()->route('admin.dashboard');

                case 'temple_manager':
                    // CORRECTED: Route name changed to 'temple-manager.dashboard'
                    return redirect()->route('temple-manager.dashboard');

                case 'hotel_manager':
                    // CORRECTED: Route name changed to 'hotel-manager.dashboard'
                    return redirect()->route('hotel-manager.dashboard');

                case 'driver':
                     // Assuming your driver route is named 'driver.dashboard'
                    return redirect()->route('driver.dashboard');

                default:
                    // Default redirect for regular users
                    return redirect()->route('home');
            }
        }

        // If login fails
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Logout
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
