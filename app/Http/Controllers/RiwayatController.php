<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Riwayat; // Tambahkan ini untuk memanggil tabel Riwayat
use Carbon\Carbon;      // Tambahkan ini untuk memformat tanggal

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
        $query = Riwayat::query();

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
                // Ambil dari 7 hari yang lalu
                $batasTanggal = \Carbon\Carbon::today()->subDays(7)->toDateString();
                $query->whereDate('created_at', '>=', $batasTanggal);
            } elseif ($waktu === 'bulan_ini') {
                // Mengunci data HANYA untuk bulan dan tahun saat ini
                $query->whereMonth('created_at', \Carbon\Carbon::now()->month)
                    ->whereYear('created_at', \Carbon\Carbon::now()->year);
            } elseif ($waktu === 'kustom') {
                // Menggunakan whereDate agar jam/menit/detik diabaikan
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
        $query = Riwayat::orderBy('created_at', 'desc');

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
    // 5. Fungsi Mengambil Data Statistik untuk Dashboard
    public function getDashboardStats(Request $request)
    {
        $device = $request->query('device', 'semua');

        $query = Riwayat::query();
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
}
