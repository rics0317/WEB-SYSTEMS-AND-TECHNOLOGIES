<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class Socialite2Controller extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function googleLogin()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function googleAuthentication()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            Log::info('Google User:', ['user' => $googleUser]);

            // Check if the user already exists in the database
            $user = User::where('google_id', $googleUser->getId())->first();

            if (!$user) {
                // Create a new user
                $userData = [
                    'name' => $googleUser->getName() ?? 'Google User',
                    'email' => $googleUser->getEmail(),
                    'password' => Hash::make(Str::random(16)),
                    'google_id' => $googleUser->getId(),
                ];

                $validator = Validator::make($userData, [
                    'name' => 'required|string|max:255',
                    'email' => 'required|string|email|max:255|unique:users',
                    'password' => 'required|string|min:8',
                    'google_id' => 'required|string|unique:users',
                ]);

                if ($validator->fails()) {
                    Log::error('Validation failed:', ['errors' => $validator->errors()]);
                    return redirect()->route('login')->withErrors($validator)->withInput()->with('error', 'Google authentication failed. Please try again.');
                }

                $user = User::create($userData);
                Log::info('New user created:', ['user' => $user]);
            }

            if ($user) {
                Auth::login($user);
                Log::info('User logged in:', ['user' => $user]);
                return redirect()->route('users.home');
            }

            throw new Exception('Failed to create or retrieve user');

        } catch (Exception $e) {
            Log::error('Google authentication error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->route('login')->with('error', 'Google authentication failed. Please try again.');
        }
    }
}

