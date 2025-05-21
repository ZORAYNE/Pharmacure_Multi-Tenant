<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            return redirect('/login')->withErrors('Unable to login using Google. Please try again.');
        }

        // Check if user already exists
        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            // Log the user in
            Auth::login($user);
            return redirect()->intended('/dashboard');
        } else {
            // Create a new user or handle as per your app logic
            $newUser = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'password' => bcrypt(str_random(16)), // random password
                // Add other fields as necessary
            ]);

            Auth::login($newUser);
            return redirect()->intended('/dashboard');
        }
    }
}
