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
    /**
     * Auth
     */
    Route::post('/logout', [
        AuthenticatedSessionController::class, 
        'destroy', 
    ])->name('logout'); 

    /**
     * Dashboard
     */
    Route::prefix('dashboard')
        ->name('dashboard')
        ->group(function () {
            Route::get('/', function () {
                return Inertia::render('Dashboard/Pages/DashboardIndex');
            })->name('');
        }); 
    /*
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard/Pages/DashboardIndex'); 
    })->name('dashboard'); 
    */
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

/**
 * Fallback route
 */
/*
Route::fallback(function () {
    return redirect()->route('dashboard');
})->middleware('auth'); 
*/

/*
Route::get('/', function () { return Inertia::render('Platform/Pages/Dashboard'); })->name('platform.dashboard');
*/
