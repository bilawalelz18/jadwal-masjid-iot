<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\KontrolController;
use App\Http\Controllers\DeviceController;

// --- Bawaan Laravel (Biarkan saja) ---
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// 1. Rute untuk ESP32 mengirim log riwayat (POST)
Route::post('/sensor-data', [RiwayatController::class, 'storeApi']);

// 2. Rute untuk Dashboard Web mengambil data statistik (GET)
Route::get('/get-dashboard-stats', [RiwayatController::class, 'getDashboardStats']);

// 3. Rute untuk ESP32 mengecek perintah semprot manual (GET)
Route::get('/cek-perintah', [RiwayatController::class, 'cekPerintah']);

// 4. Rute untuk ESP32 meminta jadwal sholat berikutnya (GET)
Route::get('/next-jadwal', [RiwayatController::class, 'getNextJadwal']);

Route::get('/test-device-data', [KontrolController::class, 'getDeviceData']);

Route::post('/test-spray', [DeviceController::class, 'spray']);