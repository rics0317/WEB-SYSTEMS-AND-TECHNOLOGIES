@extends('layouts.admin')

@section('title', 'Profile Settings')

@section('content')
<div class="profile-settings">
    <h1>Profile Settings</h1>
    <div class="profile-image">
        <img src="{{ asset(Auth::user()->profile_image) }}" alt="{{ Auth::user()->name }}">
        <form action="{{ route('admin.profile.updateImage') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="profile_image" accept="image/*">
            <button type="submit">Update Image</button>
        </form>
    </div>
    <form action="{{ route('admin.profile.update') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="{{ Auth::user()->name }}" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ Auth::user()->email }}" required>
        </div>
        <div class="form-group">
            <label for="current_password">Current Password</label>
            <input type="password" id="current_password" name="current_password" required>
        </div>
        <button type="submit">Update Profile</button>
    </form>
</div>

<style>
    .profile-settings {
        max-width: 600px;
        margin: 0 auto;
    }

    .profile-image img {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
    }

    .form-group input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    button {
        padding: 10px 20px;
        background-color: var(--color-accent);
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    button:hover {
        background-color: #0d2a4a;
    }
</style>
@endsection
