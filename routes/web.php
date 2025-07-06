<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\YouTubeController;

Route::get('/', [AuthController::class, 'showLogin'])->name('login');

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'doLogin'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/return', [AuthController::class, 'redirectToGoogleWithReturn'])->name('google.reauth');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');

Route::get('/l/{code}', [LinkController::class, 'redirect'])->name('link.redirect');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::post('/auth/refresh-subscriptions', [AuthController::class, 'refreshSubscriptions'])->name('auth.refresh-subscriptions');
    
    Route::get('/channels', [YouTubeController::class, 'index'])->name('channels.index');
    Route::post('/channels', [YouTubeController::class, 'store'])->name('channels.store');
    Route::delete('/channels/{channel}', [YouTubeController::class, 'destroy'])->name('channels.destroy');
    
    Route::get('/links', [LinkController::class, 'index'])->name('links.index');
    Route::get('/links/create', [LinkController::class, 'create'])->name('links.create');
    Route::post('/links', [LinkController::class, 'store'])->name('links.store');
    Route::get('/links/{link}/edit', [LinkController::class, 'edit'])->name('links.edit');
    Route::put('/links/{link}', [LinkController::class, 'update'])->name('links.update');
    Route::delete('/links/{link}', [LinkController::class, 'destroy'])->name('links.destroy');
    
    Route::post('/links/{link}/toggle', [LinkController::class, 'toggle'])->name('links.toggle');
});
