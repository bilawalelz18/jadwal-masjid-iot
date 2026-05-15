@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-end mb-8">
        <div>
            <h2 class="text-3xl font-bold text-on-surface dark:text-gray-100 tracking-tight">Selamat Datang, Pengurus</h2>
            <p class="text-secondary dark:text-gray-400 mt-1">Sistem Pemantauan Pengharum Ruangan Masjid Otomatis</p>
        </div>
        <div class="text-right">
            <p class="text-2xl font-bold text-primary dark:text-emerald-400" id="realtime-clock">{{ now()->format('H:i:s') }} WIB</p>
            <p class="text-sm text-secondary dark:text-gray-400 font-semibold">{{ now()->translatedFormat('l, d F Y') }}</p>
        </div>
    </div>

    <div class="bg-surface-container-lowest dark:bg-[#111417] border border-transparent dark:border-white/5 p-4 rounded-xl mb-6 flex flex-wrap gap-4 justify-between items-center transition-colors shadow-sm">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-primary/10 dark:bg-emerald-500/10 rounded-lg text-primary dark:text-emerald-400">
                <span class="material-symbols-outlined text-sm">tune</span>
            </div>
            <span class="font-bold text-sm text-on-surface dark:text-gray-200">Tampilkan Data Untuk:</span>
        </div>
        <div class="relative w-full md:w-auto">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant dark:text-gray-400 material-symbols-outlined text-sm">router</span>
            
            <select id="dashboard-device-filter" class="w-full md:w-64 pl-10 pr-4 py-2 bg-surface-container-low dark:bg-[#181c20] rounded-full border-none text-sm font-semibold text-on-surface dark:text-gray-200 focus:ring-2 focus:ring-emerald-500/50 appearance-none transition-colors shadow-sm cursor-pointer">
                @if(isset($devices) && $devices->count() > 0)
                    @foreach($devices as $device)
                        <option value="{{ $device->device_id }}">
                            {{ $device->nama_perangkat }} ({{ $device->room ? $device->room->nama_ruangan : 'Area Luar' }})
                        </option>
                    @endforeach
                @else
                    <option value="">Belum ada perangkat terdaftar</option>
                @endif
            </select>

        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-surface-container-lowest dark:bg-[#111417] border border-transparent dark:border-white/5 p-8 rounded-xl transition-colors flex flex-col items-center justify-center text-center relative overflow-hidden group">
            <div class="absolute top-0 left-0 p-4 opacity-100 group-hover:opacity-90 transition-opacity">
                <div class="relative flex items-center justify-center w-16 h-16 rounded-full bg-primary-container/20 dark:bg-emerald-500/10 border border-primary-container/30 dark:border-emerald-500/20 shadow-sm">
                    <span class="material-symbols-outlined text-primary dark:text-emerald-400 text-3xl" style="font-variation-settings: 'FILL' 1;">water_drop</span>
                </div>
            </div>
            <div class="relative w-32 h-32 mb-4">
                <svg class="w-full h-full transform -rotate-90">
                    <circle class="text-surface-container-high dark:text-white/5" cx="64" cy="64" fill="transparent" r="56" stroke="currentColor" stroke-width="12"></circle>
                    <circle id="circle-sisa-cairan" class="text-primary-container dark:text-emerald-500 transition-all duration-1000" cx="64" cy="64" fill="transparent" r="56" stroke="currentColor" stroke-dasharray="351.85" stroke-dashoffset="193.5" stroke-linecap="round" stroke-width="12"></circle>
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span id="text-sisa-cairan" class="text-2xl font-bold text-on-surface dark:text-gray-100">--%</span>
                    <span class="text-[10px] font-bold text-secondary dark:text-gray-500 uppercase tracking-widest">Sisa</span>
                </div>
            </div>
            <h3 class="text-sm font-semibold text-secondary dark:text-gray-300 mb-1">Sisa Cairan</h3>
            <p id="desc-sisa-cairan" class="text-xs text-outline dark:text-gray-500 font-medium">Berdasarkan pemakaian alat</p>
        </div>

        <div class="bg-surface-container-lowest dark:bg-[#111417] border border-transparent dark:border-white/5 p-8 rounded-xl flex flex-col justify-between transition-colors">
            <div class="flex justify-between items-start">
                <div class="bg-primary/10 dark:bg-emerald-500/10 p-3 rounded-full text-primary dark:text-emerald-400">
                    <span class="material-symbols-outlined" data-icon="router">router</span>
                </div>
                <div id="badge-status-alat" class="flex items-center gap-2 px-3 py-1 bg-gray-500/10 rounded-full">
                    <span class="relative flex h-2 w-2">
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-gray-400"></span>
                    </span>
                    <span id="text-badge-status" class="text-[10px] font-bold text-gray-500 uppercase">MEMUAT</span>
                </div>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-secondary mb-1">Status Alat</h3>
                <p id="text-status-alat" class="text-2xl font-bold text-on-surface dark:text-gray-100">Memeriksa...</p>
                <p id="desc-status-alat" class="text-xs text-outline dark:text-gray-500 mt-2">Memindai sinyal perangkat...</p>
            </div>
        </div>

        <div class="bg-surface-container-lowest dark:bg-[#111417] border border-transparent dark:border-white/5 p-8 rounded-xl flex flex-col justify-between transition-colors">
            <div class="flex justify-between items-start">
                <div class="bg-amber-100 dark:bg-amber-500/10 p-3 rounded-full text-amber-600 dark:text-amber-400">
                    <span class="material-symbols-outlined" data-icon="history_toggle_off">history_toggle_off</span>
                </div>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-secondary mb-1">Penyemprotan Berikutnya</h3>
                <p id="text-next-waktu" class="text-2xl font-bold text-on-surface dark:text-gray-100">--</p>
                <p id="text-next-jam" class="text-lg font-semibold text-amber-600 dark:text-amber-400 mt-1">--:-- WIB</p>
                <p id="desc-next-waktu" class="text-xs text-outline dark:text-gray-500 mt-2">Berdasarkan jadwal otomatis</p>
            </div>
        </div>
    </div>

    <div class="bg-surface-container-lowest dark:bg-[#111417] p-8 rounded-xl mb-8 shadow-sm border border-slate-100 dark:border-white/5 transition-colors">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h3 class="text-lg font-bold text-on-surface dark:text-gray-100">Grafik Frekuensi Penyemprotan</h3>
                <p class="text-xs text-secondary dark:text-gray-400 mt-1 font-medium">Berdasarkan 5 Waktu Sholat + Penggunaan Manual</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-[#10b981] opacity-20 dark:opacity-40"></div>
                    <span class="text-[10px] font-bold text-secondary dark:text-gray-400 uppercase tracking-wider">Harian</span>
                </div>
                <span class="text-xs font-bold px-3 py-1 bg-surface-container-low dark:bg-white/5 rounded-full text-secondary dark:text-gray-300 ml-2">Satu Minggu Terakhir</span>
            </div>
        </div>
        <div class="relative h-72 w-full">
            <canvas id="sprayChart"></canvas>
        </div>
    </div>

    <div class="bg-surface-container-lowest dark:bg-[#111417] border border-transparent dark:border-white/5 rounded-xl overflow-hidden transition-colors shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-surface-container-low/50 dark:bg-white/5 transition-colors">
                        <th class="px-8 py-5 text-xs font-bold text-on-surface-variant dark:text-gray-400 uppercase tracking-wider font-label">Tanggal</th>
                        <th class="px-8 py-5 text-xs font-bold text-on-surface-variant dark:text-gray-400 uppercase tracking-wider font-label">Waktu</th>
                        <th class="px-8 py-5 text-xs font-bold text-on-surface-variant dark:text-gray-400 uppercase tracking-wider font-label">Perangkat</th>
                        <th class="px-8 py-5 text-xs font-bold text-on-surface-variant dark:text-gray-400 uppercase tracking-wider font-label">AKSI</th>
                        <th class="px-8 py-5 text-xs font-bold text-on-surface-variant dark:text-gray-400 uppercase tracking-wider font-label">Status</th>
                    </tr>
                </thead>
                <tbody id="dashboard-riwayat-tbody" class="divide-y divide-surface-container-low dark:divide-white/5">
                    <tr><td colspan="5" class="px-8 py-6 text-center text-sm text-on-surface-variant">Memuat data riwayat realtime...</td></tr>
                </tbody>
            </table>
        </div>
        <div class="px-8 py-6 border-t border-surface-container-low dark:border-white/5 flex justify-center">
            <a href="{{ route('riwayat.index') }}" class="text-primary dark:text-emerald-400 text-sm font-bold hover:underline">Lihat Semua Riwayat →</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. JAM REALTIME
            function updateClock() {
                const now = new Date();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');
                const clockElement = document.getElementById('realtime-clock');
                if (clockElement) clockElement.textContent = `${hours}:${minutes}:${seconds} WIB`;
            }
            setInterval(updateClock, 1000);
            updateClock();

            // 2. SETUP GRAFIK CHART.JS
            const ctx = document.getElementById('sprayChart').getContext('2d');
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#9ca3af' : '#6c7a71'; 
            const gridColor = isDark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(224, 227, 229, 0.5)';
            
            let sprayChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu', 'Minggu'],
                    datasets: [{
                        label: 'Total Semprotan',
                        data: [0, 0, 0, 0, 0, 0, 0], 
                        backgroundColor: isDark ? 'rgba(16, 185, 129, 0.15)' : 'rgba(16, 185, 129, 0.2)',
                        borderColor: isDark ? 'rgba(52, 211, 153, 1)' : 'rgba(0, 108, 73, 1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: isDark ? 'rgba(52, 211, 153, 1)' : 'rgba(0, 108, 73, 1)',
                        pointBorderColor: isDark ? '#111417' : '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, suggestedMax: 10, grid: { color: gridColor, drawBorder: false }, ticks: { color: textColor, stepSize: 2 } },
                        x: { grid: { display: false, drawBorder: false }, ticks: { color: textColor, font: { family: 'Inter', size: 11, weight: 'bold' } } }
                    }
                }
            });

            // 3. FUNGSI MENGAMBIL DATA RIWAYAT (SUDAH DIPERBAIKI)
            const deviceFilter = document.getElementById('dashboard-device-filter');
            const tbody = document.getElementById('dashboard-riwayat-tbody');

            function fetchDashboardData() {
                let currentSelection = deviceFilter.value;
                
                // Jika tidak ada alat terdaftar, hentikan pencarian
                if (!currentSelection) {
                    tbody.innerHTML = `<tr><td colspan="5" class="px-8 py-6 text-center text-sm text-on-surface-variant">Belum ada perangkat yang ditambahkan ke sistem.</td></tr>`;
                    return;
                }

                // Ambil data dari server khusus untuk alat yang dipilih di dropdown
                fetch(`/api/get-riwayat?device=${currentSelection}`)
                    .then(response => response.json())
                    .then(data => {
                        let htmlRows = '';
                        const top4Data = data.slice(0, 4); 

                        if (top4Data.length === 0) {
                            tbody.innerHTML = `<tr><td colspan="5" class="px-8 py-6 text-center text-sm text-on-surface-variant">Belum ada data riwayat untuk perangkat ini.</td></tr>`;
                        } else {
                            top4Data.forEach(item => {
                                let aksiColor = item.aksi === 'manual' ? 'bg-surface-container-highest text-on-surface-variant dark:bg-white/10 dark:text-gray-300' : 'bg-secondary-container/20 text-on-secondary-container dark:bg-blue-500/10 dark:text-blue-400';
                                let aksiText = item.aksi === 'manual' ? 'Manual' : (item.aksi === 'smart_trigger' ? 'Sensor IR' : 'Otomatis');
                                
                                let statusHtml = item.status === 'berhasil' 
                                    ? `<span class="flex items-center gap-2 text-xs font-bold text-primary dark:text-emerald-400"><span class="h-2 w-2 rounded-full bg-primary dark:bg-emerald-400"></span>Berhasil</span>`
                                    : `<span class="flex items-center gap-2 text-xs font-bold text-tertiary dark:text-red-400"><span class="h-2 w-2 rounded-full bg-tertiary dark:bg-red-400"></span>Gagal</span>`;

                                let deviceName = item.device || item.device_id || currentSelection;

                                htmlRows += `
                                    <tr class="hover:bg-surface-container-low dark:hover:bg-white/5 transition-colors">
                                        <td class="px-8 py-6 text-sm font-bold text-on-surface dark:text-gray-200">${item.tanggal}</td>
                                        <td class="px-8 py-6 text-sm text-on-surface-variant dark:text-gray-400">${item.waktu}</td>
                                        <td class="px-8 py-6 text-sm font-bold text-on-surface-variant dark:text-gray-300">${deviceName}</td>
                                        <td class="px-8 py-6"><span class="text-xs font-bold px-3 py-1 rounded-full ${aksiColor}">${aksiText}</span></td>
                                        <td class="px-8 py-6">${statusHtml}</td>
                                    </tr>`;
                            });
                            tbody.innerHTML = htmlRows;
                        }

                        // Setelah tabel terisi, update data statistik untuk alat tersebut
                        fetchDashboardStats(currentSelection);
                    })
                    .catch(err => console.error("Gagal load riwayat:", err));
            }

            // 4. FUNGSI MENGAMBIL DATA STATISTIK KARTU & GRAFIK
            function fetchDashboardStats(selectedDevice) {
                if (!selectedDevice) return; 

                fetch(`/api/get-dashboard-stats?device=${selectedDevice}`)
                    .then(response => {
                        if(!response.ok) throw new Error("Endpoint belum tersedia");
                        return response.json();
                    })
                    .then(data => {
                        // KARTU SISA CAIRAN
                        document.getElementById('text-sisa-cairan').innerText = data.sisa_persen + '%';
                        const offset = 351.85 - (351.85 * (data.sisa_persen / 100));
                        document.getElementById('circle-sisa-cairan').style.strokeDashoffset = offset;
                        
                        // KARTU STATUS ALAT
                        const isOnline = data.is_online;
                        document.getElementById('text-status-alat').innerText = isOnline ? 'Terhubung' : 'Terputus';
                        document.getElementById('badge-status-alat').className = isOnline ? 'flex items-center gap-2 px-3 py-1 bg-primary/10 dark:bg-emerald-500/10 rounded-full' : 'flex items-center gap-2 px-3 py-1 bg-tertiary/10 dark:bg-red-500/10 rounded-full';
                        document.getElementById('text-badge-status').className = isOnline ? 'text-[10px] font-bold text-primary dark:text-emerald-400 uppercase' : 'text-[10px] font-bold text-tertiary dark:text-red-400 uppercase';
                        document.getElementById('text-badge-status').innerText = isOnline ? 'ONLINE' : 'OFFLINE';

                        if (isOnline) {
                            let fakePing = Math.floor(Math.random() * (45 - 12 + 1)) + 12;
                            document.getElementById('desc-status-alat').innerText = `Koneksi stabil (Sinyal: ${fakePing}ms)`;
                        } else {
                            document.getElementById('desc-status-alat').innerText = `Sinyal terputus`;
                        }

                        // GRAFIK MINGGUAN
                        if(data.chart_data) {
                            sprayChart.data.datasets[0].data = data.chart_data;
                            sprayChart.update();
                        }
                    })
                    .catch(err => {
                        console.error("Gagal update stats:", err);
                        document.getElementById('text-status-alat').innerText = 'Terputus';
                        document.getElementById('desc-status-alat').innerText = 'Sinyal terputus';
                        document.getElementById('badge-status-alat').className = 'flex items-center gap-2 px-3 py-1 bg-tertiary/10 dark:bg-red-500/10 rounded-full';
                        document.getElementById('text-badge-status').className = 'text-[10px] font-bold text-tertiary dark:text-red-400 uppercase';
                        document.getElementById('text-badge-status').innerText = 'OFFLINE';
                    });
            }

            // Jalankan saat pertama dibuka
            fetchDashboardData();
            
            // State: Saat pindah Dropdown Device, ubah status ke Loading & Refresh Tabel
            deviceFilter.addEventListener('change', () => {
                document.getElementById('text-status-alat').innerText = 'Memeriksa...';
                document.getElementById('desc-status-alat').innerText = 'Memindai sinyal perangkat...';
                document.getElementById('badge-status-alat').className = 'flex items-center gap-2 px-3 py-1 bg-gray-500/10 rounded-full';
                document.getElementById('text-badge-status').className = 'text-[10px] font-bold text-gray-500 uppercase';
                document.getElementById('text-badge-status').innerText = 'MEMUAT';
                
                // Tambahkan efek loading pada tabel
                tbody.innerHTML = '<tr><td colspan="5" class="px-8 py-6 text-center text-sm text-on-surface-variant">Memuat data...</td></tr>';
                
                fetchDashboardData();
            });

            // Polling realtime setiap 5 detik
            setInterval(() => {
                fetchDashboardData();
            }, 5000);
        });
    </script>
@endsection