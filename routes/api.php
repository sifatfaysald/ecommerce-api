<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\API\CartController;

/*
|----------------------------------------------------------------------|
| API Routes                                                          |
|----------------------------------------------------------------------|
*/

// ✅ Root Test Route (GET method, for browser testing)
Route::get('/', function () {
    return response()->json(['message' => '👋 Welcome to the E-commerce API']);
});

// 🟢 Public Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);

// 🔐 Protected Routes (only for logged-in users)
Route::middleware('auth:sanctum')->group(function () {
    // Logout Route
    Route::post('/logout', [AuthController::class, 'logout']);

    // Product Routes
    Route::get('/products', [ProductController::class, 'index']); // Get all products
    Route::post('/products', [ProductController::class, 'store']); // Create a new product
    Route::get('/products/{id}', [ProductController::class, 'show']); // Get a specific product
    Route::put('/products/{id}', [ProductController::class, 'update']); // Update a product
    Route::delete('/products/{id}', [ProductController::class, 'destroy']); // Delete a product

    // Cart Routes
    Route::post('/cart', [CartController::class, 'addToCart']); // Add a product to the cart
    Route::get('/cart', [CartController::class, 'getCart']); // Get all items in the cart
    Route::delete('/cart/{id}', [CartController::class, 'removeFromCart']); // Remove a product from the cart
});
