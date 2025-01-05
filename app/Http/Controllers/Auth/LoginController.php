<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = 'users/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    protected function redirectTo()
    {
        $user = Auth::user();

        if ($user->role->role_name === 'customer') {
            return '/users/home';
        } elseif ($user->role->role_name === 'admin') {
            return '/admin/dashboard';
        }

        return $this->redirectTo;
    }

    protected function authenticated(Request $request, $user)
    {
        // Check if the user's email is verified
        if (!$user->hasVerifiedEmail()) {
            // Send verification email to the registered email
            $user->sendEmailVerificationNotification();

            // Redirect to the email verification notice page
            return redirect()->route('verification.notice');
        }

        // Set the user's status to online
        $user->status = true;
        $user->save();

        return redirect()->intended($this->redirectPath());
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        // Set the user's status to offline
        $user->status = false;
        $user->save();

        $this->guard()->logout();

        $request->session()->invalidate();

        // Redirect to /users/home after logout
        return $this->loggedOut($request) ?: redirect('/users/home');
    }

    public function redirectPath()
    {
        return $this->redirectTo();
    }
}
