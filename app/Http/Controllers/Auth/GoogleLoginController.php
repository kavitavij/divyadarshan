<?php

namespace App\Http\Controllers\Auth; // Adjust namespace if needed

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use Illuminate\Support\Carbon;

class GoogleLoginController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google and log them in or register them.
     */
    public function handleGoogleCallback()
    {
        try {
            // Using stateless() might be necessary if sessions aren't persisting correctly
            // across the redirect, though typically not needed with database/file sessions.
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Check if user already exists by email first
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // User exists by email, ensure google_id is updated (or already set)
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'google_token' => $googleUser->token,
                    'google_refresh_token' => $googleUser->refreshToken ?? $user->google_refresh_token ?? null, // Keep existing refresh token if new one isn't provided
                    'email_verified_at' => $user->email_verified_at ?? Carbon::now(), // Mark verified if not already
                ]);
            } else {
                 // User doesn't exist by email, try finding by google_id (in case email changed on Google's side)
                 $user = User::where('google_id', $googleUser->getId())->first();
                 if ($user) {
                     // Found by google_id, update details
                     $user->update([
                        'name' => $googleUser->getName(),
                        'email' => $googleUser->getEmail(),
                        'google_token' => $googleUser->token,
                        'google_refresh_token' => $googleUser->refreshToken ?? $user->google_refresh_token ?? null,
                        'email_verified_at' => $user->email_verified_at ?? Carbon::now(),
                     ]);
                 } else {
                    // User truly doesn't exist, create new one
                    $user = User::create([
                        'name' => $googleUser->getName(),
                        'email' => $googleUser->getEmail(),
                        'google_id' => $googleUser->getId(),
                        'google_token' => $googleUser->token,
                        'google_refresh_token' => $googleUser->refreshToken ?? null,
                        'password' => Hash::make(str()->random(24)), // Create a random password
                        'email_verified_at' => Carbon::now(), // Verify email for Google users
                    ]);
                 }
            }


            // Log user activity
            \Log::info('Google Login Successful', [
                'user_id' => $user->id,
                'email' => $user->email,
                'session_id' => session()->getId(),
            ]);

            Auth::login($user);

            // ---> UPDATED REDIRECT <---
            // Redirect to the intended page, defaulting to the home route ('/')
            return redirect()->intended(route('home'));
            // Or simply: return redirect(route('home')); if you don't need intended redirection

        } catch (Exception $e) {
            \Log::error('Google Login Failed', [
                'error' => $e->getMessage(),
            ]);
            report($e); // Log the exception more formally
            return redirect('/login')->with('error', 'Unable to login using Google. Please try again.');
        }
    }
}