<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Riwayat;
use Carbon\Carbon;

class RiwayatController extends Controller
{
    // 1. Menampilkan halaman riwayat di web (HTML)
    public function index()
    {
        return view('riwayat');
    }

    // 2. Fungsi untuk Web Dashboard (Ambil data real-time via AJAX JSON)
    public function getData(Request $request)
    {
        // 1. PURIFIKASI: Ambil list ID yang SAH dari tabel devices
        $validDeviceIds = \App\Models\Device::pluck('device_id')->toArray();

        // 2. Query riwayat HANYA untuk ID yang SAH
        $query = Riwayat::whereIn('device_id', $validDeviceIds);

        // A. Cek Filter Perangkat
        if ($request->filled('device') && $request->device !== 'semua') {
            $query->where('device_id', $request->device);
        }

        // B. Cek Filter Status
        if ($request->filled('status') && $request->status !== 'Semua') {
            $query->where('status', strtolower($request->status));
        }

        // C. Cek Filter Waktu
        if ($request->filled('waktu') && $request->waktu !== 'semua') {
            $waktu = $request->waktu;

            if ($waktu === '7_hari') {
                $batasTanggal = \Carbon\Carbon::today()->subDays(7)->toDateString();
                $query->whereDate('created_at', '>=', $batasTanggal);
            } elseif ($waktu === 'bulan_ini') {
                $query->whereMonth('created_at', \Carbon\Carbon::now()->month)
                    ->whereYear('created_at', \Carbon\Carbon::now()->year);
            } elseif ($waktu === 'kustom') {
                if ($request->filled('start')) {
                    $query->whereDate('created_at', '>=', $request->start);
                }
                if ($request->filled('end')) {
                    $query->whereDate('created_at', '<=', $request->end);
                }
            }
        }

        // Ambil 50 data terbaru dari hasil filter
        $data = $query->orderBy('created_at', 'desc')->take(50)->get();

        $formattedData = $data->map(function ($item) {
            return [
                'tanggal' => \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y'),
                'waktu'   => \Carbon\Carbon::parse($item->created_at)->format('H:i') . ' WIB',
                'device'  => $item->device_id,
                'aksi'    => $item->trigger_aksi,
                'status'  => strtolower($item->status)
            ];
        });

        return response()->json($formattedData);
    }

    // 4. Logika untuk mencetak PDF (Sesuai Filter)
    public function exportPdf(Request $request)
    {
        // 1. PURIFIKASI: Ambil list ID yang SAH dari tabel devices
        $validDeviceIds = \App\Models\Device::pluck('device_id')->toArray();

        // 2. Query riwayat HANYA untuk ID yang SAH
        $query = Riwayat::whereIn('device_id', $validDeviceIds)->orderBy('created_at', 'desc');

        if ($request->filled('device') && $request->device !== 'semua') {
            $query->where('device_id', $request->device);
        }

        if ($request->filled('status') && $request->status !== 'Semua') {
            $query->where('status', strtolower($request->status));
        }

        if ($request->filled('waktu') && $request->waktu !== 'semua') {
            $waktu = $request->waktu;

            if ($waktu === '7_hari') {
                $batasTanggal = \Carbon\Carbon::today()->subDays(7)->toDateString();
                $query->whereDate('created_at', '>=', $batasTanggal);
            } elseif ($waktu === 'kustom') {
                if ($request->filled('start')) {
                    $query->whereDate('created_at', '>=', $request->start);
                }
                if ($request->filled('end')) {
                    $query->whereDate('created_at', '<=', $request->end);
                }
            }
        }

        // PDF mengekspor seluruh data yang tersaring (tanpa batasan 50)
        $dataRiwayat = $query->get();

        $pdf = Pdf::loadView('riwayat-pdf', compact('dataRiwayat'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('Laporan_Riwayat_Penyemprotan.pdf');
    }

    // 5. Fungsi Mengambil Data Statistik untuk Dashboard
    public function getDashboardStats(Request $request)
    {
        $device = $request->query('device', 'semua');

        // 1. PURIFIKASI: Ambil list ID yang SAH
        $validDeviceIds = \App\Models\Device::pluck('device_id')->toArray();

        // 2. Query HANYA untuk ID yang SAH
        $query = Riwayat::whereIn('device_id', $validDeviceIds);

        if ($device !== 'semua') {
            $query->where('device_id', $device);
        }

        // --- A. GRAFIK 7 HARI TERAKHIR ---
        $chartData = [];
        // Cari tanggal hari Senin minggu ini
        $startOfWeek = \Carbon\Carbon::now()->startOfWeek();

        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i)->toDateString();

            // Cek jika harinya belum terjadi (di masa depan), biarkan 0
            if (\Carbon\Carbon::parse($date)->isFuture()) {
                $chartData[] = 0;
            } else {
                $count = (clone $query)->whereDate('created_at', $date)->count();
                $chartData[] = $count;
            }
        }

        // --- B. SISA CAIRAN (Persentase) ---
        $latestRiwayat = (clone $query)->latest()->first();
        $sisaMl = $latestRiwayat ? $latestRiwayat->sisa_parfum_ml : 0;
        $kapasitasMax = 1000;

        $sisaPersen = 0;
        if ($kapasitasMax > 0) {
            $sisaPersen = round(($sisaMl / $kapasitasMax) * 100);
            if ($sisaPersen > 100) $sisaPersen = 100;
            if ($sisaPersen < 0) $sisaPersen = 0;
        }

        // --- C. STATUS ONLINE ALAT (PERBAIKAN SINKRONISASI) ---
        // Langsung cek ke tabel Device berdasarkan device_id
        $deviceModel = \App\Models\Device::where('device_id', $device)->first();
        $isOnline = false;

        // Jika alat ditemukan di tabel Device dan status_koneksi-nya 'online'
        if ($deviceModel && strtolower($deviceModel->status_koneksi) === 'online') {
            $isOnline = true;
        }

        return response()->json([
            'chart_data'  => $chartData,
            'sisa_persen' => $sisaPersen,
            'is_online'   => $isOnline
        ]);
    }

    // 6. Fungsi API untuk menerima data dari ESP32 (DENGAN KEAMANAN API KEY)
    public function storeApi(Request $request)
    {
        try {
            $request->validate([
                'device_id'    => 'required',
                'api_key'      => 'required',
                'trigger_aksi' => 'required',
                'status'       => 'required',
            ]);

            $device = \App\Models\Device::where('device_id', $request->device_id)
                ->where('api_key', $request->api_key)
                ->first();

            if (!$device) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Akses Ditolak! Device ID tidak terdaftar atau API Key salah.'
                ], 401);
            }

            $riwayat = new Riwayat();
            $riwayat->device_id = $request->device_id;
            $riwayat->trigger_aksi = $request->trigger_aksi;
            $riwayat->status = $request->status;

            if ($request->has('sisa_parfum_ml')) {
                $riwayat->sisa_parfum_ml = $request->sisa_parfum_ml;
            } else {
                $riwayat->sisa_parfum_ml = 0;
            }

            $riwayat->save();

            return response()->json([
                'status'  => 'success',
                'message' => 'Akses Diterima! Data riwayat berhasil disimpan.'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    // 7. Fungsi API untuk ESP32: Cek Jadwal Sholat Terdekat (Multi-Ruangan)
    public function getNextJadwal(Request $request)
    {
        $deviceId = $request->device_id;

        if (!$deviceId) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Device ID tidak disertakan di URL!'
            ], 400);
        }

        $device = \App\Models\Device::where('device_id', $deviceId)->first();

        if (!$device) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Alat tidak terdaftar di sistem!'
            ], 404);
        }

        $now = \Carbon\Carbon::now('Asia/Jakarta');
        $jamSekarang = $now->format('H:i:s');
        $tanggalHariIni = $now->format('Y-m-d');

        $jadwalTerdekat = \App\Models\JadwalSholat::where('room_id', $device->room_id)
            ->where(function ($query) use ($tanggalHariIni) {
                $query->where('tanggal', $tanggalHariIni)
                    ->orWhereNull('tanggal');
            })
            ->where('waktu', '>', $jamSekarang)
            ->orderBy('waktu', 'asc')
            ->first();

        if (!$jadwalTerdekat) {
            $besok = \Carbon\Carbon::tomorrow('Asia/Jakarta')->format('Y-m-d');

            $jadwalTerdekat = \App\Models\JadwalSholat::where('room_id', $device->room_id)
                ->where(function ($query) use ($besok) {
                    $query->where('tanggal', $besok)
                        ->orWhereNull('tanggal');
                })
                ->orderBy('waktu', 'asc')
                ->first();
        }

        if ($jadwalTerdekat) {
            $jamFormat = \Carbon\Carbon::parse($jadwalTerdekat->waktu)->format('H:i');

            return response()->json([
                'next_jadwal' => $jadwalTerdekat->nama_sholat,
                'jam'         => $jamFormat,
                'status'      => 'success'
            ]);
        } else {
            return response()->json([
                'next_jadwal' => '-',
                'jam'         => '-',
                'status'      => 'error',
                'message'     => 'Belum ada data jadwal untuk ruangan ini.'
            ], 404);
        }
    }

    // 8. Fungsi API untuk ESP32 mengecek perintah manual
    public function cekPerintah(Request $request)
    {
        $device = \App\Models\Device::where('device_id', $request->device_id)
            ->where('api_key', $request->api_key)
            ->first();

        if (!$device) {
            return response()->json(['perintah' => 'TOLAK', 'message' => 'Kunci Salah!'], 401);
        }

        if ($device->perintah_semprot == 1) {
            $device->perintah_semprot = 0;
            $device->save();
            return response()->json(['perintah' => 'SEMPROT_SEKARANG']);
        }

        return response()->json(['perintah' => 'TUNGGU']);
    }
}
