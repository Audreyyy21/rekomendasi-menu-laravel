<?php

use App\Http\Controllers\RecommendationController;

Route::get('/rekomendasi', [RecommendationController::class, 'index']);
Route::get('/rekomendasi/generate', [RecommendationController::class, 'generate']);
Route::get('/rekomendasi/history', [RecommendationController::class, 'history']);
