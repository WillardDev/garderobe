<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ClothingItemController;

Route::get('/', function () {
    return view('welcome');
});

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('clothing-items', ClothingItemController::class);
    Route::get('/categories', [CategoryController::class, 'index']);
});