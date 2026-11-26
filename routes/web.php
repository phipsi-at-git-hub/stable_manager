<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Localization\LocaleController;
use Illuminate\Support\Facades\Route; 
use Inertia\Inertia; 

/**
 * Routes for guests
 */
Route::middleware('guest')->group(function () {
    Route::get('/login', [
        AuthenticatedSessionController::class, 
        'create', 
    ])->name('login'); 

    Route::post('/login', [
        AuthenticatedSessionController::class, 
        'store', 
    ])->name('login.store'); 
}); 

/**
 * Routes for logged in users
 */
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Platform/Pages/Dashboard'); 
    })->name('platform.dashboard'); 

    Route::post('/logout', [
        AuthenticatedSessionController::class, 
        'destroy', 
    ])->name('logout'); 
}); 

/**
 * Routes outside middleware 
 */
Route::post('/locale', [
    LocaleController::class, 
    'update', 
])->name('locale.update'); 

/**
 * Routes for development
 */
Route::get('/test', function () { return Inertia::render('Dev/Pages/Test'); })->name('test'); 

/*
Route::get('/', function () { return Inertia::render('Platform/Pages/Dashboard'); })->name('platform.dashboard');
*/
