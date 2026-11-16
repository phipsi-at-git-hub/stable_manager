<?php

use Illuminate\Support\Facades\Route; 
use Inertia\Inertia; 

Route::get('/', function () { return Inertia::render('Platform/Pages/Dashboard'); })->name('platform.dashboard');
Route::get('/test', function () { return Inertia::render('Dev/Pages/Test'); })->name('test'); 
