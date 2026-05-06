<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Riwayat Penyemprotan</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            margin: 20px;
        }
        h2 {
            text-align: center;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .subtitle {
            text-align: center;
            font-size: 12px;
            color: #555;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px 12px;
            text-align: left;
            font-size: 12px;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-transform: uppercase;
        }
        .text-success { color: #10b981; font-weight: bold; }
        .text-danger { color: #ef4444; font-weight: bold; }
    </style>
</head>
<body>

    <h2>Laporan Riwayat Penyemprotan</h2>
    <div class="subtitle">Masjid As-Salam - Diekspor pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB</div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Perangkat</th>
                <th>Aksi</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($dataRiwayat as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }} WIB</td>
                    <td>{{ $item->device_id ?? 'Alat-01' }}</td>
                    <td>
                        @if($item->trigger_aksi == 'manual')
                            Manual
                        @elseif($item->trigger_aksi == 'smart_trigger')
                            Sensor IR
                        @else
                            Otomatis
                        @endif
                    </td>
                    <td>
                        @if(strtolower($item->status) == 'berhasil')
                            <span class="text-success">Berhasil</span>
                        @else
                            <span class="text-danger">Gagal</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Belum ada data riwayat penyemprotan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>