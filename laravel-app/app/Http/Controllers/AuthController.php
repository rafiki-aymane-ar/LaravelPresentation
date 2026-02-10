<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

/**
 * Handles user authentication via Laravel Socialite (Google OAuth).
 */
class AuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle the callback from Google.
     * 
     * - Retreives user info from Google.
     * - Creates or updates the user in the database.
     * - Logs the user in and redirects to the quiz.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::updateOrCreate([
                'email' => $googleUser->email, // Match by email first to prevent duplicates
            ], [
                'name' => $googleUser->name,
                'google_id' => $googleUser->id,
                'avatar' => $googleUser->avatar,
                'password' => null, // Google users don't have password initially
            ]);

            Auth::login($user);

            return redirect('/quiz');
        } catch (\Exception $e) {
            return redirect('/quiz')->with('error', 'Erreur Google: ' . $e->getMessage());
        }
    }

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::logout();
        return redirect('/quiz');
    }
}
