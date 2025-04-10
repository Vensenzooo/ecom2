<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredClientController;
use Illuminate\Support\Facades\Route;

// Routes pour les visiteurs non authentifiés
Route::middleware('guest')->group(function () {
    // Login
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // Signup (inscription client)
    Route::get('signup', [RegisteredClientController::class, 'create'])
        ->name('signup');
        
    Route::post('signup', [RegisteredClientController::class, 'store']);
});

// Routes pour les utilisateurs authentifiés
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
