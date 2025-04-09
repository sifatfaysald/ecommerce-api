<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\SalesmanController;
use App\Http\Controllers\Api\UserController;

/*
|---------------------------------------------------------------------------
| API Routes
|---------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public routes (No authentication required)

// User registration route
Route::post('register', [AuthController::class, 'register']);

// User login route
Route::post('login', [AuthController::class, 'login']);

// Authenticated routes (Require Sanctum authentication)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Admin routes (requires 'admin' role)
Route::middleware('auth:sanctum', 'role:admin')->group(function () {
    Route::get('admin/dashboard', [AdminController::class, 'dashboard']);
    // Add more admin-specific routes here
});

// Salesman routes (requires 'salesman' role)
Route::middleware('auth:sanctum', 'role:salesman')->group(function () {
    Route::get('salesman/dashboard', [SalesmanController::class, 'dashboard']);
    // Add more salesman-specific routes here
});

// User routes (requires 'user' role)
Route::middleware('auth:sanctum', 'role:user')->group(function () {
    Route::get('user/dashboard', [UserController::class, 'dashboard']);
    // Add more user-specific routes here
});
