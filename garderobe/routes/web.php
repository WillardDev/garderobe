<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ClothingItemController;
use App\Http\Controllers\API\CategoryController;

// API Test Route (publicly accessible for debugging)
Route::get('/api/test', function () {
    return response()->json(['message' => 'API is working']);
});

// API routes - MUST come BEFORE the catch-all route
Route::prefix('api')->group(function () {
    // Public routes
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    
    // User route for checking authentication
    Route::middleware('auth:sanctum')->get('/user', function () {
        return auth()->user();
    });

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        // Clothing Items (full resource routes)
        Route::get('/clothing-items', [ClothingItemController::class, 'index']);
        Route::post('/clothing-items', [ClothingItemController::class, 'store']);
        Route::get('/clothing-items/{clothingItem}', [ClothingItemController::class, 'show']);
        Route::put('/clothing-items/{clothingItem}', [ClothingItemController::class, 'update']);
        Route::delete('/clothing-items/{clothingItem}', [ClothingItemController::class, 'destroy']);
        
        // Categories
        Route::get('/categories', [CategoryController::class, 'index']);
    });
});

// SPA fallback route - any non-API route should return the Vue app
Route::get('/{any?}', function () {
    return view('welcome');
})->where('any', '.*');
