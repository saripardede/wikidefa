<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Admin API Routes
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('/admin/data', [AdminController::class, 'index']);
    Route::post('/admin/update-status/{id}', [AdminController::class, 'updateStatus']);
});

// User API Routes
Route::middleware(['auth:sanctum', 'user'])->group(function () {
    Route::get('/user/data', [UserController::class, 'index']);
});

// Authentication Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
