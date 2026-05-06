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
    public function getData()
    {
        // Ambil 10 data terbaru
        $data = Riwayat::orderBy('created_at', 'desc')->take(10)->get();

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

    // 3. Fungsi untuk alat ESP32 (Menerima Laporan masuk via HTTP POST JSON)
    public function storeApi(Request $request)
    {
        $riwayat = Riwayat::create([
            'device_id' => $request->device_id ?? '01',
            'trigger_aksi' => $request->trigger_aksi,
            'status' => $request->status,
            'sisa_parfum_ml' => $request->sisa_parfum_ml ?? 0
        ]);

        return response()->json(['status' => 'success', 'message' => 'Laporan dari ESP32 berhasil dicatat!']);
    }

    // 4. Logika untuk mencetak PDF (Sekarang menggunakan data asli dari Database)
    // 4. Logika untuk mencetak PDF (Sesuai Filter)
    public function exportPdf(Request $request)
    {
        $query = Riwayat::orderBy('created_at', 'desc');

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
            $today = \Carbon\Carbon::today();

            if ($waktu === '7_hari') {
                $query->where('created_at', '>=', $today->copy()->subDays(7));
            } elseif ($waktu === '30_hari') {
                $query->where('created_at', '>=', $today->copy()->subDays(30));
            } elseif ($waktu === 'bulan_ini') {
                $query->whereMonth('created_at', $today->month)
                    ->whereYear('created_at', $today->year);
            } elseif ($waktu === 'kustom') {
                if ($request->filled('start')) {
                    $query->where('created_at', '>=', $request->start . ' 00:00:00');
                }
                if ($request->filled('end')) {
                    $query->where('created_at', '<=', $request->end . ' 23:59:59');
                }
            }
        }

        // Ambil data yang sudah disaring
        $dataRiwayat = $query->get();

        // Load view PDF dan kirim datanya
        $pdf = Pdf::loadView('riwayat-pdf', compact('dataRiwayat'));
        $pdf->setPaper('A4', 'portrait');

        // Mengunduh file PDF
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
