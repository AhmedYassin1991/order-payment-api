<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\PaymentController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes (Require JWT Authentication)
Route::middleware('auth:api')->group(function () {
    // Order Management Routes
    Route::apiResource('orders', OrderController::class);

    // Payment Management Routes
    Route::post('/orders/{order}/payments', [PaymentController::class, 'processPayment']);
    Route::get('/orders/{order}/payments', [PaymentController::class, 'index']);
});
