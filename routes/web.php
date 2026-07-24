<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});
Route::get('/go-to-bims', [AuthController::class, 'redirect'])->name('go-to-bims');
Route::get('/auth/callback', [AuthController::class, 'callback'])->name('auth.callback');
require __DIR__.'/settings.php';
