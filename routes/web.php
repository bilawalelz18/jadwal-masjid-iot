<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JadwalSholatController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\DeviceController;
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
        return view('dashboard');
    })->name('dashboard');

    Route::get('/kontrol-manual', function () {
        // Ambil data perangkat dari database untuk mengisi dropdown
        $devices = Device::with('room')->get();
        return view('kontrol-manual', compact('devices'));
    })->name('kontrol.manual');

    // Rute Riwayat & Cetak PDF
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
    Route::get('/riwayat/export-pdf', [RiwayatController::class, 'exportPdf'])->name('riwayat.export_pdf');
    Route::get('/api/get-riwayat', [RiwayatController::class, 'getData'])->name('riwayat.data');

    // Notifikasi
    Route::get('/notifikasi', function () {
        \App\Models\Notifikasi::where('created_at', '<', now()->subDays(30))->delete();
        $notifikasis = Notifikasi::latest()->paginate(5);
        return view('notifikasi', compact('notifikasis'));
    })->name('notifikasi.index');
    Route::post('/kontrol-manual/spray', [App\Http\Controllers\DeviceController::class, 'spray'])->name('kontrol.spray');

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

Route::get('/devices', [DeviceController::class, 'index'])->name('devices.index');
Route::post('/devices', [DeviceController::class, 'store'])->name('devices.store');
Route::delete('/devices/{id}', [DeviceController::class, 'destroy'])->name('devices.destroy');
Route::post('/devices/{id}/reset-api', [DeviceController::class, 'resetApiKey'])->name('devices.reset');
