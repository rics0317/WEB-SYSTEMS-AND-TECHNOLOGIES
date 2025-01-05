<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/email/verify';

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        try {
            $this->validator($request->all())->validate();

            $validatedData = $this->validateRegisterDetails($request->all());

            $user = $this->create($validatedData);

            event(new Registered($user));

            // Send email verification notification
            $user->sendEmailVerificationNotification();

            // Log the user in
            $this->guard()->login($user);

            // Redirect to the email verification notice page
            return redirect($this->redirectPath());
        } catch (ValidationException $e) {
            Log::error('Validation failed: ' . $e->getMessage());
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Registration failed: ' . $e->getMessage());
            return redirect()->back()->withErrors(['email' => 'An error occurred during registration.'])->withInput();
        }
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        ]);
    }

    protected function validateRegisterDetails(array $data)
    {
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ])->validate();
    }

    protected function create(array $data)
    {
        // Fetch the 'customer' role
        $role = Role::where('role_name', 'customer')->first();

        if (!$role) {
            Log::error('Customer role not found.');
            throw new \Exception('Customer role not found.');
        }

        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $role->id, // Assign the 'customer' role
        ]);
    }

    protected function redirectPath()
    {
        return $this->redirectTo;
    }
}

