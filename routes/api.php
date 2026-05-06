<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RiwayatController;

// --- Bawaan Laravel (Biarkan saja) ---
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/sensor-data', [RiwayatController::class, 'storeApi']);
Route::get('/get-dashboard-stats', [RiwayatController::class, 'getDashboardStats']);