@extends('layouts.app')

@section('content')
<div class="video-background">
    <video autoplay muted loop id="background-video">
        <source src="{{ asset('assets/mp4/Mine.mp4') }}" type="video/mp4">
        Your browser does not support the video tag.
    </video>
</div>

<div class="container">
    <h2 class="text-center text-white mb-4">Add New User</h2>

    <!-- Form to create a new user -->
    <form action="{{ route('admin.users.store') }}" method="POST" class="mb-4">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label text-white">Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label text-white">Email</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label text-white">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="role" class="form-label text-white">Role</label>
            <select name="role" id="role" class="form-select" required>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Create User</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Back to User List</a>
    </form>
</div>

<link rel="stylesheet" href="{{ asset('css/indexblade.css') }}">
@endsection
