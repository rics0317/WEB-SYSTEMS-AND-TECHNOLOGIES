<?php

namespace App\Http\Controllers;

use App\Models\User; // Make sure to import the User model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Import the Hash facade

class ManageController extends Controller
{
    public function index()
    {
        // Fetch all users from the database
        $users = User::paginate(10); // Adjust the number to the desired per-page limit
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create'); // Make sure to create this view
    }

    public function updateRole(Request $request, $id)
    {
        // Validate the incoming request
        $request->validate([
            'role' => 'required|in:user,admin', // Ensure the role is either user or admin
        ]);

        // Find the user by ID and update their role
        $user = User::findOrFail($id);
        $user->role = $request->role;
        $user->save(); // Save the changes

        // Redirect back to the user management page with a success message
        return redirect()->route('admin.users.index')->with('success', 'User role updated successfully.');
    }

    public function destroy($id)
    {
        // Find the user by ID and delete them
        $user = User::findOrFail($id);
        $user->delete(); // Delete the user

        // Redirect back to the user management page with a success message
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:user,admin',
            'password' => 'required|string|min:6', // Ensure a password is provided
        ]);

        // Create a new user
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password), // Hash the password
        ]);

        // Redirect back to the user management page with a success message
        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }
}
