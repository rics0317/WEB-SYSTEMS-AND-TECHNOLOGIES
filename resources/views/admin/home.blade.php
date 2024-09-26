@extends('layouts.app')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Minecraft Marketplace')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/homeblade.css') }}">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <style>
        body {
            background-image: url('{{ asset('assets/img/MC-2.jpg') }}'); /* Update with correct path */
            background-size: cover; /* Cover the entire area */
            background-position: center; /* Center the background image */
            height: 100vh; /* Full height */
            margin: 0; /* Remove default margin */
            display: flex;
            flex-direction: column;
        }
    </style>
</head>
<body>

        <!-- Button Container -->
        <div class="top-right-buttons">
            <!-- Admin Buttons -->
            @if(Auth::check() && Auth::user()->role === 'admin')
                <div class="admin-buttons">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-primary btn-lg m-2">
                        <i class="fas fa-box"></i> Products
                    </a>
                    <a href="{{ route('admin.category.index') }}" class="btn btn-secondary btn-lg m-2">
                        <i class="fas fa-list"></i> Category
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-lg m-2">
                        <i class="fas fa-users"></i> Manage User
                    </a>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary btn-lg m-2">
                        <i class="fas fa-user"></i> Profile
                    </a>
                </div>
            @endif

            <!-- User Buttons -->
            @if(Auth::check() && Auth::user()->role === 'user')
                <div class="user-buttons">
                    <a href="{{ route('users.products.index') }}" class="btn btn-primary btn-lg m-2">
                        <i class="fas fa-box"></i> Shop
                    </a>
                    <a href="{{ route('users.profile.updateprofile') }}" class="btn btn-secondary btn-lg m-2">
                        <i class="fas fa-user"></i> Profile
                    </a>
                </div>
            @endif
            
            <!-- Logout Button -->
<div class="logout-button">
    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
        @csrf
        <button type="submit" class="btn btn-danger btn-lg m-2">
            <i class="fas fa-sign-out-alt"></i> Logout
        </button>
    </form>
</div>

        </div>
    </header>

    <!-- Flash Message Section -->
    @if(session('success'))
        <div class="flash-message">
            {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="flash-message" style="background-color: #f44336;"> <!-- Red background for errors -->
            {{ session('error') }}
        </div>
    @endif

    @yield('content')
</body>
</html>
