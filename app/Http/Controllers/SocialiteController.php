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

class SocialiteController extends Controller
{
    public function authProviderRedirect($provider) {
        if ($provider) {
            return Socialite::driver($provider)->redirect();
        }
        abort(404);
    }

    public function socialAuthentication($provider) {
        try {
            if ($provider) {
                $socialUser = Socialite::driver($provider)->user();

                Log::info('Social User:', ['user' => $socialUser]);

                $user = User::where('auth_provider_id', $socialUser->id)
                            ->where('auth_provider', $provider)
                            ->first();

                if (!$user) {
                    $userData = [
                        'name' => $socialUser->name ?? $socialUser->nickname ?? 'User',
                        'email' => $socialUser->email ?? $socialUser->id . '@' . $provider . '.com',
                        'password' => Hash::make(Str::random(16)),
                        'auth_provider_id' => $socialUser->id,
                        'auth_provider' => $provider,
                    ];

                    $validator = Validator::make($userData, [
                        'name' => 'required|string|max:255',
                        'email' => 'required|string|email|max:255|unique:users',
                        'password' => 'required|string|min:8',
                        'auth_provider_id' => 'required|string',
                        'auth_provider' => 'required|string',
                    ]);

                    if ($validator->fails()) {
                        Log::error('Validation failed:', ['errors' => $validator->errors()]);
                        return redirect()->route('login')->withErrors($validator)->withInput()->with('error', 'Validation failed. Please check your input.');
                    }

                    $user = User::create($userData);
                    Log::info('New user created:', ['user' => $user]);
                }

                if ($user) {
                    Auth::login($user);
                    Log::info('User logged in:', ['user' => $user]);
                    return redirect()->route('users.home');
                }
            }
            abort(404);

        } catch (Exception $e) {
            Log::error('Socialite Authentication Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Authentication failed. Please try again.');
        }
    }
}

