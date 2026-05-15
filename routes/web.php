<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JadwalSholatController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\KontrolController;
use App\Models\Device;
use App\Models\Notifikasi;

// --- RUTE YANG BISA DIAKSES TANPA LOGIN ---
Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');


// --- RUTE YANG DIKUNCI (HARUS LOGIN DULU) ---
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', function () {
        // Ambil data perangkat dari database untuk dikirim ke dropdown dashboard
        $devices = \App\Models\Device::with('room')->get();

        return view('dashboard', compact('devices'));
    })->name('dashboard');

    // Rute Kontrol Manual
    Route::get('/kontrol-manual', [KontrolController::class, 'index'])->name('kontrol.manual');
    Route::post('/kontrol-manual/spray', [DeviceController::class, 'spray'])->name('kontrol.spray');
    Route::get('/kontrol-manual/api/device-data', [KontrolController::class, 'getDeviceData'])->name('kontrol.device_data');

    // Rute Riwayat & Cetak PDF (TELAH DIPERBARUI)
    Route::get('/riwayat', function () {
        $devices = \App\Models\Device::with('room')->get();
        return view('riwayat', compact('devices'));
    })->name('riwayat.index');

    Route::get('/riwayat/export-pdf', [RiwayatController::class, 'exportPdf'])->name('riwayat.export_pdf');
    Route::get('/api/get-riwayat', [RiwayatController::class, 'getData'])->name('riwayat.data');

    // Notifikasi
    Route::get('/notifikasi', function () {
        \App\Models\Notifikasi::where('created_at', '<', now()->subDays(30))->delete();
        $notifikasis = Notifikasi::latest()->paginate(5);
        return view('notifikasi', compact('notifikasis'));
    })->name('notifikasi.index');

    // Rute Jadwal
    Route::post('/jadwal/sync', [JadwalSholatController::class, 'sync'])->name('jadwal.sync');
    Route::delete('/jadwal/destroy-all', [JadwalSholatController::class, 'destroyAll'])->name('jadwal.destroyAll');
    Route::resource('jadwal', JadwalSholatController::class);

    // Rute Pengaturan
    Route::get('/pengaturan', function () {
        return view('pengaturan');
    })->name('pengaturan.index');

    Route::get('/pengaturan/edit', function () {
        return view('pengaturan-edit');
    })->name('pengaturan.edit');

    Route::put('/pengaturan/update', [AuthController::class, 'updateProfile'])->name('pengaturan.update');
});

// --- RUTE PERANGKAT (DEVICES) ---
Route::get('/devices', [DeviceController::class, 'index'])->name('devices.index');
Route::post('/devices', [DeviceController::class, 'store'])->name('devices.store');
Route::delete('/devices/{id}', [DeviceController::class, 'destroy'])->name('devices.destroy');
Route::post('/devices/{id}/reset-api', [DeviceController::class, 'resetApiKey'])->name('devices.reset');
