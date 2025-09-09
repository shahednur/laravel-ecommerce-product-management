<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use Inertia\Inertia;


// Web routes for Inertia pages
Route::resource('products', ProductController::class);
// Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
// Route::post('/products', [ProductController::class, 'store'])->name('products.store');

// // API routes
// Route::apiResource('products', ProductController::class)->except(['index', 'create', 'store']);
// Route::apiResource('categories', CategoryController::class);

// // Category–Product relationships
// Route::post('products/{product}/categories/{category}', [ProductController::class, 'attachCategory'])
//     ->name('products.attachCategory');

// Route::delete('products/{product}/categories/{category}', [ProductController::class, 'detachCategory'])
//     ->name('products.detachCategory');

// Route::get('products/{product}/categories', [ProductController::class, 'categories'])
//     ->name('products.categories');

// Route::get('categories/{category}/products', [CategoryController::class, 'products'])
//     ->name('categories.products');
