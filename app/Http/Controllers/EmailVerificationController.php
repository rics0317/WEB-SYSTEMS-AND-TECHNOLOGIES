<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailVerificationController extends Controller
{
    public function show()
    {
        return view('auth.verify-email');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|string|size:4',
        ]);

        $user = Auth::user();

        if ($user->verification_code === $request->verification_code) {
            $user->email_verified_at = now();
            $user->verification_code = null;
            $user->save();

            return redirect('/users/home')->with('success', 'Email verified successfully!');
        }

        return back()->withErrors(['verification_code' => 'Invalid verification code.']);
    }

    public function resend()
    {
        $user = Auth::user();
        
        // Generate new verification code
        $verificationCode = sprintf('%04d', mt_rand(0, 9999));
        $user->verification_code = $verificationCode;
        $user->save();

        // Resend verification email
        Mail::to($user->email)->send(new VerificationCode($user));

        return back()->with('success', 'Verification code resent. Please check your email.');
    }
}