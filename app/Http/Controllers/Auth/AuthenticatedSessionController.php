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

            // Redirect by role
            switch ($user->role) {
                case 'Admin':
                    return redirect()->route('admin.dashboard');
                case 'Temple Manager':
                    return redirect()->route('temple.dashboard');
                case 'Hotel Manager':
                    return redirect()->route('hotel.dashboard');
                case 'Driver':
                    return redirect()->route('driver.dashboard');
                default:
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
