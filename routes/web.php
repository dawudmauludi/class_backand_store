<?php

use App\Http\Controllers\CategoryProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('categories', CategoryProductController::class)
        ->only(['index', 'store', 'update', 'destroy']);

    Route::resource('products', ProductController::class)
        ->except(['show']);
});

require __DIR__.'/settings.php';
