<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\Room;
use App\Models\Notifikasi;
use Illuminate\Support\Str;
use App\Models\Riwayat;

class DeviceController extends Controller
{
    public function index()
    {
        $devices = Device::with('room')->get();
        $rooms = Room::all();

        return view('devices.index', compact('devices', 'rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_perangkat' => 'required|string|max:255',
            'room_id' => 'required|exists:rooms,id',
            'device_id' => 'required|string|unique:devices,device_id',
        ]);

        $apiKey = 'as-salam-' . Str::random(24);

        Device::create([
            'nama_perangkat' => $request->nama_perangkat,
            'room_id' => $request->room_id,
            'device_id' => $request->device_id,
            'api_key' => $apiKey,
            'status_koneksi' => 'offline'
        ]);

        $namaRuangan = Room::find($request->room_id)->nama_ruangan;

        Notifikasi::create([
            'judul' => 'Perangkat Baru Ditambahkan',
            'pesan' => "Perangkat IoT baru ({$request->nama_perangkat}) berhasil ditambahkan ke lokasi {$namaRuangan}.",
            'tipe'  => 'success', // HIJAU
            'ikon'  => 'router'
        ]);

        return back()->with('success', 'Perangkat IoT baru berhasil ditambahkan! Silakan salin API Key untuk dipasang di ESP32.');
    }

    public function destroy($id)
    {
        $device = Device::findOrFail($id);
        $namaPerangkat = $device->nama_perangkat;

        $device->delete();

        Notifikasi::create([
            'judul' => 'Perangkat Dihapus',
            'pesan' => "Perangkat IoT ({$namaPerangkat}) telah dihapus secara permanen dari sistem.",
            'tipe'  => 'error', // MERAH
            'ikon'  => 'delete_forever'
        ]);

        return back()->with('success', 'Perangkat berhasil dihapus dari sistem.');
    }

    public function resetApiKey($id)
    {
        $device = Device::findOrFail($id);

        $device->update([
            'api_key' => 'as-salam-' . Str::random(24)
        ]);

        Notifikasi::create([
            'judul' => 'API Key Perangkat Direset',
            'pesan' => "API Key untuk perangkat {$device->nama_perangkat} telah direset. Segera flash ulang ESP32 Anda dengan kunci yang baru.",
            'tipe'  => 'info', // BIRU
            'ikon'  => 'key'
        ]);

        return back()->with('success', 'API Key berhasil direset. Jangan lupa perbarui kode di ESP32 Anda.');
    }

    public function spray(Request $request)
    {
        $target = $request->target;
        $deviceName = 'Semua Perangkat (Broadcast)';

        if ($target !== 'all') {
            // JIKA TARGETNYA HANYA SATU ALAT
            $device = Device::where('device_id', $target)->first();
            if ($device) {
                $deviceName = $device->nama_perangkat;

                // 1. TEKAN SAKLAR UNTUK ESP32
                $device->perintah_semprot = 1;
                $device->save();

                // 2. Catat ke tabel Riwayat untuk alat ini
                Riwayat::create([
                    'device_id' => $device->device_id,
                    'trigger_aksi' => 'manual',
                    'status' => 'berhasil',
                    'sisa_parfum_ml' => 0 // Kita isi 0 sementara karena cairan akan di-update oleh ESP32 nanti
                ]);
            }
        } else {
            // JIKA TARGETNYA 'ALL' (SEMUA ALAT / BROADCAST)
            $allDevices = Device::all();

            foreach ($allDevices as $dev) {
                // 1. TEKAN SAKLAR UNTUK SETIAP ESP32
                $dev->perintah_semprot = 1;
                $dev->save();

                // 2. Catat ke tabel Riwayat untuk setiap alat
                Riwayat::create([
                    'device_id' => $dev->device_id,
                    'trigger_aksi' => 'manual',
                    'status' => 'berhasil',
                    'sisa_parfum_ml' => 0
                ]);
            }
        }

        // Membuat Notifikasi untuk Dashboard
        Notifikasi::create([
            'judul' => 'Penyemprotan Manual Diaktifkan',
            'pesan' => "Sistem mengeksekusi perintah penyemprotan manual untuk target: {$deviceName}.",
            'tipe'  => 'success',  // HIJAU
            'ikon'  => 'tune'
        ]);

        return response()->json([
            'success' => true,
            'message' => "Berhasil dikirim ke {$deviceName}"
        ]);
    }
}
