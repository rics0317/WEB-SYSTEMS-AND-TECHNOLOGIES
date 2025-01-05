<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        return view('admin.profile.change-prof', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'gender' => 'required|string',
            'civil_status' => 'required|string',
            'contact' => 'required|string',
            'age' => 'required|integer',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile-images', 'public');
            $user->profile_image = Storage::url($path);
        }

        // Update other profile information
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->birthdate = $request->input('birthdate');
        $user->gender = $request->input('gender');
        $user->civil_status = $request->input('civil_status');
        $user->contact = $request->input('contact');
        $user->age = $request->input('age');
        $user->email = $request->input('email');

        $user->save();

        return redirect()->route('admin.profile')->with('success', 'Profile updated successfully.');
    }

    public function changePassword()
    {
        $user = Auth::user();
        return view('admin.profile.change-pass', compact('user'));
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required|string|min:8',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        // Check if the current password matches
        if (!Hash::check($request->input('current_password'), $user->password)) {
            return response()->json(['errors' => ['current_password' => ['The current password is incorrect.']]], 422);
        }

        // Update the password
        $user->password = Hash::make($request->input('new_password'));
        $user->save();

        return response()->json(['success' => 'Password updated successfully.']);
    }
}
