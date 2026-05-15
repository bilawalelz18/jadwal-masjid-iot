<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\Riwayat;
use App\Models\JadwalSholat;
use Carbon\Carbon;

class KontrolController extends Controller
{
    // 1. Tampilan Awal (Default: Semua Perangkat / Global)
    public function index()
    {
        $devices = Device::with('room')->get();

        // AMBIL DAFTAR ID ALAT YANG RESMI (MENGHINDARI DATA HANTU POSTMAN)
        $validDeviceIds = $devices->pluck('device_id')->toArray();

        // A. Hitung Rata-rata Sisa Cairan HANYA dari alat yang valid
        $latestRiwayats = Riwayat::whereIn('device_id', $validDeviceIds) // <-- KUNCI PERBAIKAN
            ->whereIn('id', function ($query) {
                $query->selectRaw('MAX(id)')->from('riwayats')->groupBy('device_id');
            })->get();

        $sisaMl = $latestRiwayats->avg('sisa_parfum_ml') ?? 0;

        $sisaPersen = round(($sisaMl / 1000) * 100);
        $sisaPersen = $sisaPersen > 100 ? 100 : ($sisaPersen < 0 ? 0 : $sisaPersen);
        $sisaSemprotan = round($sisaMl / 2);

        // B. Waktu Aktif Terakhir HANYA dari alat yang valid
        $lastSpray = Riwayat::whereIn('device_id', $validDeviceIds) // <-- KUNCI PERBAIKAN
            ->orderBy('created_at', 'desc')->first();

        // C. Jadwal Global Hari Ini
        $now = Carbon::now('Asia/Jakarta');
        $jamSekarang = $now->format('H:i:s');
        $tanggalHariIni = $now->format('Y-m-d');

        $jadwalSholats = JadwalSholat::where(function ($query) use ($tanggalHariIni) {
            $query->where('tanggal', $tanggalHariIni)->orWhereNull('tanggal');
        })
            ->orderBy('waktu', 'asc')
            ->get()
            ->unique('nama_sholat')
            ->values();

        $jadwalTerdekat = $jadwalSholats->where('waktu', '>', $jamSekarang)->first();

        return view('kontrol-manual', compact(
            'devices',
            'lastSpray',
            'sisaPersen',
            'sisaSemprotan',
            'jadwalSholats',
            'jadwalTerdekat'
        ));
    }

    // 2. Fungsi AJAX untuk mengambil data spesifik 1 Alat / Semua Alat
    public function getDeviceData(Request $request)
    {
        $deviceId = $request->device_id;
        $now = Carbon::now('Asia/Jakarta');
        $jamSekarang = $now->format('H:i:s');
        $tanggalHariIni = $now->format('Y-m-d');

        if ($deviceId === 'all') {
            // Jika user memilih kembali "Semua Perangkat"
            $validDeviceIds = Device::pluck('device_id')->toArray(); // <-- KUNCI PERBAIKAN

            $latestRiwayats = Riwayat::whereIn('device_id', $validDeviceIds) // <-- KUNCI PERBAIKAN
                ->whereIn('id', function ($query) {
                    $query->selectRaw('MAX(id)')->from('riwayats')->groupBy('device_id');
                })->get();

            $sisaMl = $latestRiwayats->avg('sisa_parfum_ml') ?? 0;

            $lastSpray = Riwayat::whereIn('device_id', $validDeviceIds) // <-- KUNCI PERBAIKAN
                ->orderBy('created_at', 'desc')->first();

            $jadwalSholats = JadwalSholat::where(function ($query) use ($tanggalHariIni) {
                $query->where('tanggal', $tanggalHariIni)->orWhereNull('tanggal');
            })
                ->orderBy('waktu', 'asc')->get()->unique('nama_sholat')->values();
        } else {
            // Jika user memilih Alat Spesifik
            $device = Device::where('device_id', $deviceId)->first();
            $lastSpray = Riwayat::where('device_id', $deviceId)->orderBy('created_at', 'desc')->first();
            $sisaMl = $lastSpray ? $lastSpray->sisa_parfum_ml : 0;

            if ($device && $device->room_id) {
                $jadwalSholats = JadwalSholat::where('room_id', $device->room_id)
                    ->where(function ($query) use ($tanggalHariIni) {
                        $query->where('tanggal', $tanggalHariIni)->orWhereNull('tanggal');
                    })
                    ->orderBy('waktu', 'asc')->get();
            } else {
                $jadwalSholats = collect([]);
            }
        }

        // Kalkulasi Akhir
        $sisaPersen = round(($sisaMl / 1000) * 100);
        $sisaPersen = $sisaPersen > 100 ? 100 : ($sisaPersen < 0 ? 0 : $sisaPersen);

        $jadwalTerdekat = $jadwalSholats->where('waktu', '>', $jamSekarang)->first();

        // Format data Jadwal
        $jadwalArray = [];
        foreach ($jadwalSholats as $j) {
            $jadwalArray[] = [
                'nama_sholat' => $j->nama_sholat,
                'waktu'       => Carbon::parse($j->waktu)->format('H:i'),
                'is_passed'   => $j->waktu <= $jamSekarang,
                'is_next'     => $jadwalTerdekat && $j->id == $jadwalTerdekat->id
            ];
        }

        return response()->json([
            'sisaPersen'        => $sisaPersen,
            'sisaSemprotan'     => round($sisaMl / 2),
            'lastSprayTime'     => $lastSpray ? Carbon::parse($lastSpray->created_at)->timezone('Asia/Jakarta')->format('H:i') . ' WIB' : '--:-- WIB',
            'lastSprayRelative' => $lastSpray ? Carbon::parse($lastSpray->created_at)->timezone('Asia/Jakarta')->diffForHumans() : 'Belum ada data',
            'jadwals'           => $jadwalArray
        ]);
    }
}
