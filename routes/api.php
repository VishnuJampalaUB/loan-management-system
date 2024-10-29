<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\AuthController;

// Auth endpoints
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Loan GET routes without authentication
Route::get('loans', [LoanController::class, 'index']);
Route::get('loans/{loan}', [LoanController::class, 'show']);

// Loan CRUD routes with authentication
Route::middleware('auth:sanctum')->group(function () {
    Route::post('loans', [LoanController::class, 'store']);
    Route::put('loans/{loan}', [LoanController::class, 'update']);
    Route::delete('loans/{loan}', [LoanController::class, 'destroy']);
});


