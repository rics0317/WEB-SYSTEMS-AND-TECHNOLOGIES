<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController; // Import the HomeController
use App\Http\Controllers\ProductController; // Import the ProductController

// Route for the home page
Route::get('/', [HomeController::class, 'index']);

// Routes for handling product-related requests
Route::resource('products', ProductController::class);
// In routes/web.php
Route::get('/', [HomeController::class, 'index'])->name('home');
