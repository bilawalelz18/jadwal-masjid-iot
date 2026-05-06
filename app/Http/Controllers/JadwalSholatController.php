<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalSholat;
use App\Models\Room;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class JadwalSholatController extends Controller
{
    public function index(Request $request)
    {
        $today = date('Y-m-d');
        $showAll = $request->query('all') === '1';
        $rooms = Room::all();
        $roomId = $request->query('room_id') ?? ($rooms->first() ? $rooms->first()->id : null);
        $query = JadwalSholat::with('room');
        if (!$showAll) {
            $query->where('tanggal', $today);
        }
        if ($roomId) {
            $query->where('room_id', $roomId);
        }
        $jadwals = $query->orderBy('tanggal', 'asc')->orderBy('waktu', 'asc')->get();
        $hasOtherDateQuery = JadwalSholat::where('tanggal', '!=', $today);
        if ($roomId) {
            $hasOtherDateQuery->where('room_id', $roomId);
        }
        $hasOtherDate = !$showAll && $hasOtherDateQuery->exists();

        return view('jadwal.index', compact('jadwals', 'showAll', 'hasOtherDate', 'rooms', 'roomId'));
    }

    public function create()
    {
        $rooms = Room::all();
        return view('jadwal.create', compact('rooms'));
    }

    public function edit($id)
    {
        $jadwal = JadwalSholat::findOrFail($id);
        $rooms = Room::all();
        return view('jadwal.edit', compact('jadwal', 'rooms'));
    }

    public function sync(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id'
        ]);

        $tahun = date('Y');
        $bulan = date('m');
        $response = Http::get("https://api.myquran.com/v2/sholat/jadwal/1638/{$tahun}/{$bulan}");

        if ($response->successful()) {
            $jadwalSebulan = $response->json()['data']['jadwal'];
            $sholatWajib = ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'];
            $roomId = $request->room_id;

            foreach ($jadwalSebulan as $hari) {
                $tanggal = $hari['date'];
                foreach ($sholatWajib as $s) {
                    JadwalSholat::updateOrCreate(
                        ['nama_sholat' => ucfirst($s), 'tanggal' => $tanggal, 'room_id' => $roomId],
                        ['waktu' => $hari[$s], 'is_api' => true]
                    );
                }
            }

            $namaRuangan = Room::find($roomId)->nama_ruangan;

            Notifikasi::create([
                'judul' => 'Sinkronisasi Jadwal Berhasil',
                'pesan' => "Jadwal sholat 1 bulan ({$bulan}/{$tahun}) berhasil ditarik dari API Kemenag khusus untuk {$namaRuangan}.",
                'tipe'  => 'info', // BIRU
                'ikon'  => 'sync'
            ]);

            return back()->with('success', "Jadwal 1 bulan ({$bulan}/{$tahun}) berhasil ditarik khusus untuk {$namaRuangan}!");
        }
        return back()->with('error', 'Gagal terhubung ke API Kemenag.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_mulai',
            'nama_sholat' => 'required|string|max:255',
            'waktu' => 'required',
            'room_id' => 'required|exists:rooms,id'
        ]);

        $startDate = Carbon::parse($request->tanggal_mulai);
        $endDate = Carbon::parse($request->tanggal_akhir);

        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            JadwalSholat::updateOrCreate(
                [
                    'tanggal' => $date->format('Y-m-d'),
                    'nama_sholat' => $request->nama_sholat,
                    'room_id' => $request->room_id
                ],
                [
                    'waktu' => $request->waktu,
                    'is_api' => false
                ]
            );
        }

        $namaRuangan = Room::find($request->room_id)->nama_ruangan;

        Notifikasi::create([
            'judul' => 'Jadwal Manual Ditambahkan',
            'pesan' => "Jadwal penyemprotan manual ({$request->nama_sholat}) berhasil ditambahkan ke {$namaRuangan}.",
            'tipe'  => 'success', // HIJAU
            'ikon'  => 'add_circle'
        ]);

        return redirect()->route('jadwal.index')->with('success', 'Jadwal manual berjangka berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nama_sholat' => 'required|string|max:255',
            'waktu' => 'required',
            'room_id' => 'required|exists:rooms,id'
        ]);

        $jadwal = JadwalSholat::findOrFail($id);
        $jadwal->update([
            'tanggal' => $request->tanggal,
            'nama_sholat' => $request->nama_sholat,
            'waktu' => $request->waktu,
            'room_id' => $request->room_id,
            'is_api' => false
        ]);

        $namaRuangan = Room::find($request->room_id)->nama_ruangan;

        Notifikasi::create([
            'judul' => 'Jadwal Diperbarui',
            'pesan' => "Jadwal penyemprotan ({$request->nama_sholat}) di {$namaRuangan} berhasil diperbarui.",
            'tipe'  => 'warning', // KUNING
            'ikon'  => 'edit'
        ]);

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $jadwal = JadwalSholat::findOrFail($id);
        $namaSholat = $jadwal->nama_sholat;

        $namaRuangan = 'Semua Ruangan';
        if ($jadwal->room_id) {
            $room = Room::find($jadwal->room_id);
            if ($room) $namaRuangan = $room->nama_ruangan;
        }

        $jadwal->delete();

        Notifikasi::create([
            'judul' => 'Jadwal Dihapus',
            'pesan' => "Jadwal penyemprotan ({$namaSholat}) di {$namaRuangan} telah dihapus dari sistem.",
            'tipe'  => 'error', // MERAH
            'ikon'  => 'delete'
        ]);

        return back()->with('success', 'Jadwal berhasil dihapus!');
    }

    public function destroyAll(Request $request)
    {
        // 1. Tangkap ID Ruangan dari form
        $roomId = $request->input('room_id');

        if ($roomId) {
            // Jika ada ID Ruangan, HANYA hapus jadwal di ruangan tersebut
            JadwalSholat::where('room_id', $roomId)->delete();

            $namaRuangan = Room::find($roomId)->nama_ruangan;

            Notifikasi::create([
                'judul' => 'Jadwal Ruangan Dikosongkan',
                'pesan' => "Semua data jadwal penyemprotan di {$namaRuangan} telah dihapus bersih.",
                'tipe'  => 'error', // MERAH
                'ikon'  => 'delete_sweep'
            ]);

            return back()->with('success', "Semua data jadwal di {$namaRuangan} berhasil dikosongkan!");
        } else {
            // Jika kosong (mungkin karena belum ada ruangan sama sekali), Hapus Semua Total
            JadwalSholat::truncate();

            Notifikasi::create([
                'judul' => 'Seluruh Jadwal Sistem Dihapus',
                'pesan' => "Semua data jadwal penyemprotan di seluruh ruangan telah dikosongkan.",
                'tipe'  => 'error', // MERAH
                'ikon'  => 'delete_sweep'
            ]);

            return back()->with('success', 'Semua data jadwal berhasil dikosongkan!');
        }
    }
}
