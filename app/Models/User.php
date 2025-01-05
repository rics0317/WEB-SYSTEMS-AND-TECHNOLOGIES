<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'profile_image',
        'birthdate',
        'gender',
        'civil_status',
        'contact',
        'age',
        'status',
        'auth_provider_id',
        'auth_provider',
        'google_id',
        'role_id', // Add role_id to fillable
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'birthdate' => 'date',
    ];

    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function hasRole($role)
    {
        return $this->role->role_name === $role;
    }

    public function markEmailAsVerified()
    {
        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    public function hasVerifiedEmail()
    {
        return !is_null($this->email_verified_at);
    }
    public function addresses()
{
    return $this->hasMany(Address::class);
}
public function orders()
{
    return $this->hasMany(Order::class);
}
}
