<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\DashboardController;

/*
|-------------------------------
| AUTH (Login / Logout)
|-------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|-------------------------------
| HALAMAN SETELAH LOGIN
|-------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/rekomendasi', [RecommendationController::class, 'index']);
    Route::get('/rekomendasi/generate', [RecommendationController::class, 'generate']);
    Route::get('/rekomendasi/history', [RecommendationController::class, 'history']);
});
