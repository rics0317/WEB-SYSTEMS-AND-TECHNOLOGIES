<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ManageController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserProductController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\UserMiddleware;

// Route for the home page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication routes
Auth::routes();
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Routes for handling product-related requests
Route::resource('products', ProductController::class);

// Admin routes protected by AdminMiddleware
Route::middleware([AdminMiddleware::class])->group(function () {
    
    // Admin home page
    Route::get('admin/home', [HomeController::class, 'index'])->name('admin.home');
    Route::get('/admin/products/index', [ProductController::class, 'index'])->name('admin.products.index'); 
    // Admin routes for product management
    Route::get('/admin/products/create', [ProductController::class, 'create'])->name('admin.products.create');
    Route::post('/admin/products', [ProductController::class, 'store'])->name('admin.products.store');
    Route::get('/admin/products', [ProductController::class, 'index'])->name('admin.products.index');

    // Routes for editing, updating, and deleting products
    Route::get('/admin/products/{product}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/admin/products/{product}', [ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/admin/products/{product}', [ProductController::class, 'destroy'])->name('admin.products.destroy');

    // Category routes
    Route::resource('admin/category', CategoryController::class);
    Route::get('admin/category', [CategoryController::class, 'index'])->name('admin.category.index');
    Route::get('admin/category/create', [CategoryController::class, 'create'])->name('admin.category.create');
    Route::post('/admin/category', [CategoryController::class, 'store'])->name('admin.category.store');
    Route::get('/admin/category/{id}/edit', [CategoryController::class, 'edit'])->name('admin.category.edit');
    Route::put('admin/category/{id}', [CategoryController::class, 'update'])->name('admin.category.update');

    // User management routes
    Route::get('/admin/users', [ManageController::class, 'index'])->name('admin.users.index');
    Route::put('/admin/users/{id}/role', [ManageController::class, 'updateRole'])->name('admin.users.updateRole');
    Route::delete('/admin/users/{id}', [ManageController::class, 'destroy'])->name('admin.users.destroy');
    Route::get('/admin/users/create', [ManageController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [ManageController::class, 'store'])->name('admin.users.store');
});

// Routes for user-specific actions (not protected by AdminMiddleware)
Route::middleware([UserMiddleware::class])->group(function () {
    Route::get('users/products/index', [UserProductController::class, 'index'])->name('users.products.index');
    Route::get('users/profile/index', [UserProfileController::class, 'index'])->name('users.profile.index');
    Route::put('users/profile/update', [UserProfileController::class, 'update'])->name('users.profile.updateprofile');
});

    





