<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\RedirectResponse;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Authenticate the user
        $request->authenticate();
        $request->session()->regenerate();

        // Flash message on successful login
        $message = 'Logged in successfully.';

        // Redirect based on user role
        if (Auth::user()->role == 'admin') {
            return redirect(route('admin/home'))->with('success', $message);
        }

        return redirect()->intended(route('home', absolute: false))->with('success', $message);
    }

    /**
     * Log the user out of the application.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Log out the user
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Flash message on logout
        return redirect('/')->with('success', 'Logged out successfully.');
    }
}
