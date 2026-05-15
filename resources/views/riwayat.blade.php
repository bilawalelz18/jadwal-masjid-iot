@extends('layouts.app')

@section('content')
<style>
    .jewel-gradient {
        background: linear-gradient(135deg, #006c49 0%, #10b981 100%);
    }
</style>

<div class="px-12 py-8 relative z-10">
    <div class="mb-10">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-primary dark:text-emerald-400 hover:text-primary/80 dark:hover:text-emerald-300 transition-colors mb-4">
            <span class="material-symbols-outlined">arrow_back</span>
            Kembali ke Dashboard
        </a>
        <h2 class="text-3xl font-extrabold text-on-surface dark:text-gray-100 font-headline tracking-tight transition-colors duration-300">Log Aktivitas Penyemprotan</h2>
        <p class="text-on-surface-variant dark:text-gray-400 mt-2 max-w-2xl transition-colors duration-300">Daftar lengkap riwayat penyemprotan otomatis dan manual untuk menjaga kesucian dan kebersihan area masjid secara berkala.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
        <div class="bg-surface-container-lowest dark:bg-[#111417] p-8 rounded-xl flex flex-col justify-between h-48 border border-outline-variant/10 dark:border-white/5 transition-colors duration-300">
            <div class="flex justify-between items-start">
                <div class="p-3 bg-secondary-container/30 dark:bg-gray-800 rounded-lg text-secondary dark:text-gray-400">
                    <span class="material-symbols-outlined">analytics</span>
                </div>
                <span class="text-xs font-bold text-secondary dark:text-gray-400 uppercase tracking-widest font-label">Bulan Ini</span>
            </div>
            <div>
                <p class="text-4xl font-extrabold font-headline text-on-surface dark:text-gray-100" id="stat-total">0</p>
                <p class="text-sm font-semibold text-on-surface-variant dark:text-gray-400">Total Penyemprotan</p>
            </div>
        </div>
        <div class="bg-surface-container-lowest dark:bg-[#111417] p-8 rounded-xl flex flex-col justify-between h-48 border border-outline-variant/10 dark:border-white/5 transition-colors duration-300">
            <div class="flex justify-between items-start">
                <div class="p-3 bg-primary-container/20 dark:bg-emerald-500/10 rounded-lg text-primary dark:text-emerald-400">
                    <span class="material-symbols-outlined">task_alt</span>
                </div>
                <span class="text-xs font-bold text-primary dark:text-emerald-400 uppercase tracking-widest font-label">Status Berhasil</span>
            </div>
            <div>
                <p class="text-4xl font-extrabold font-headline text-primary dark:text-emerald-400" id="stat-sukses">0</p>
                <p class="text-sm font-semibold text-on-surface-variant dark:text-gray-400">Penyemprotan Berhasil</p>
            </div>
        </div>
        <div class="bg-surface-container-lowest dark:bg-[#111417] p-8 rounded-xl flex flex-col justify-between h-48 border border-outline-variant/10 dark:border-white/5 transition-colors duration-300">
            <div class="flex justify-between items-start">
                <div class="p-3 bg-tertiary-container/20 dark:bg-red-500/10 rounded-lg text-tertiary dark:text-red-400">
                    <span class="material-symbols-outlined">report_problem</span>
                </div>
                <span class="text-xs font-bold text-tertiary dark:text-red-400 uppercase tracking-widest font-label">Status Gagal</span>
            </div>
            <div>
                <p class="text-4xl font-extrabold font-headline text-tertiary dark:text-red-400" id="stat-gagal">0</p>
                <p class="text-sm font-semibold text-on-surface-variant dark:text-gray-400">Penyemprotan Gagal</p>
            </div>
        </div>
    </div>

    <div class="bg-surface-container-low dark:bg-[#111417] border border-transparent dark:border-white/5 p-6 rounded-lg transition-colors duration-300 mb-8 flex flex-wrap items-start justify-between gap-6">
        <div class="flex items-start gap-4 flex-1 min-w-[300px] flex-col lg:flex-row">
            
            <div class="relative flex-1 w-full lg:w-auto flex flex-col">
                <div class="relative w-full">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant dark:text-gray-400 material-symbols-outlined">calendar_today</span>
                    <select id="filter-waktu" class="w-full pl-12 pr-4 py-3 bg-surface-container-lowest dark:bg-[#181c20] rounded-full border-none text-sm font-semibold text-on-surface dark:text-gray-200 shadow-sm dark:shadow-none focus:ring-2 focus:ring-emerald-500/50 appearance-none transition-colors duration-300">
                        <option value="semua">Semua Waktu</option>
                        <option value="7_hari">7 Hari Terakhir</option>
                        <option value="bulan_ini">Bulan Ini</option>
                        <option value="kustom">Pilih Tanggal Kustom</option>
                    </select>
                </div>
                
                <div id="custom-date-inputs" class="hidden w-full mt-3 flex items-center gap-2 animate-in fade-in slide-in-from-top-2 duration-300">
                    <input type="date" id="custom-start" class="w-full px-4 py-2 bg-surface-container-lowest dark:bg-[#181c20] rounded-xl border border-outline-variant/20 dark:border-white/10 text-sm text-on-surface dark:text-gray-200 focus:ring-2 focus:ring-emerald-500/50 shadow-sm" style="color-scheme: dark light;">
                    <span class="text-on-surface-variant dark:text-gray-500 text-sm font-bold"> - </span>
                    <input type="date" id="custom-end" class="w-full px-4 py-2 bg-surface-container-lowest dark:bg-[#181c20] rounded-xl border border-outline-variant/20 dark:border-white/10 text-sm text-on-surface dark:text-gray-200 focus:ring-2 focus:ring-emerald-500/50 shadow-sm" style="color-scheme: dark light;">
                </div>
            </div>

            <div class="relative flex-1 w-full lg:w-auto flex flex-col">
                <div class="relative w-full">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant dark:text-gray-400 material-symbols-outlined">router</span>
                    <select id="filter-device" class="w-full pl-12 pr-4 py-3 bg-surface-container-lowest dark:bg-[#181c20] rounded-full border-none text-sm font-semibold text-on-surface dark:text-gray-200 shadow-sm dark:shadow-none focus:ring-2 focus:ring-emerald-500/50 appearance-none transition-colors duration-300">
                        <option value="semua">Semua Perangkat</option>
                        @if(isset($devices) && $devices->count() > 0)
                            @foreach($devices as $device)
                                <option value="{{ $device->device_id }}">
                                    {{ $device->nama_perangkat }} ({{ $device->room ? $device->room->nama_ruangan : 'Area Luar' }})
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            
            <div class="flex bg-surface-container-highest dark:bg-[#181c20] p-1 rounded-full w-full lg:w-auto overflow-x-auto" id="filter-status-container">
                <button type="button" class="status-btn px-6 py-2 rounded-full text-sm font-bold bg-surface-container-lowest dark:bg-[#111417] shadow-sm text-primary dark:text-emerald-400 transition-colors whitespace-nowrap">Semua</button>
                <button type="button" class="status-btn px-6 py-2 rounded-full text-sm font-bold text-on-surface-variant dark:text-gray-500 hover:text-on-surface dark:hover:text-gray-300 transition-colors whitespace-nowrap">Berhasil</button>
                <button type="button" class="status-btn px-6 py-2 rounded-full text-sm font-bold text-on-surface-variant dark:text-gray-500 hover:text-on-surface dark:hover:text-gray-300 transition-colors whitespace-nowrap">Gagal</button>
            </div>
        </div>
        
        <a href="{{ route('riwayat.export_pdf') }}" id="btn-export-pdf" class="jewel-gradient px-8 py-3 rounded-full text-white font-bold text-sm flex items-center justify-center gap-2 hover:opacity-90 transition-opacity shadow-lg shadow-primary/20 mt-1">
            <span class="material-symbols-outlined text-sm">download</span>
            Ekspor PDF
        </a>
    </div>

    <div id="area-cetak" class="bg-surface-container-lowest dark:bg-[#111417] border border-transparent dark:border-white/5 rounded-xl overflow-hidden transition-colors duration-300 shadow-sm">
        
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
                <tbody id="riwayat-tbody" class="divide-y divide-surface-container-low dark:divide-white/5">
                   </tbody>
            </table>
        </div>

        <div class="px-8 py-6 bg-surface-container-low/30 dark:bg-white/5 border-t border-surface-container-low dark:border-white/5 flex items-center justify-between transition-colors">
            <p id="entry-info" class="text-xs font-semibold text-on-surface-variant dark:text-gray-400">Menampilkan 0 dari 0 entri</p>
            <div class="flex items-center gap-2">
                <button class="h-10 w-10 flex items-center justify-center rounded-full text-on-surface-variant hover:bg-surface-container-high dark:hover:bg-white/10 transition-colors">
                    <span class="material-symbols-outlined">chevron_left</span>
                </button>
                <button class="h-10 w-10 flex items-center justify-center rounded-full bg-primary dark:bg-emerald-500 text-white dark:text-[#0a0c0e] font-bold text-sm shadow-md">1</button>
                <button class="h-10 w-10 flex items-center justify-center rounded-full text-on-surface-variant hover:bg-surface-container-high dark:hover:bg-white/10 transition-colors">
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="fixed bottom-0 right-0 p-12 pointer-events-none opacity-5 dark:opacity-[0.02] select-none z-0">
    <span class="material-symbols-outlined text-[15rem]" style="font-variation-settings: 'FILL' 1;">mosque</span>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // ---- VARIABEL FILTER ----
        const filterWaktu = document.getElementById('filter-waktu');
        const filterDevice = document.getElementById('filter-device'); 
        const customDateInputs = document.getElementById('custom-date-inputs');
        const customStart = document.getElementById('custom-start');
        const customEnd = document.getElementById('custom-end');
        const statusBtns = document.querySelectorAll('.status-btn');
        const tbody = document.getElementById('riwayat-tbody');
        const entryInfo = document.getElementById('entry-info');

        let currentStatusFilter = 'Semua';

        // ---- 1. LOGIKA TOMBOL STATUS ----
        statusBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                statusBtns.forEach(b => {
                    b.className = "status-btn px-6 py-2 rounded-full text-sm font-bold text-on-surface-variant dark:text-gray-500 hover:text-on-surface dark:hover:text-gray-300 transition-colors whitespace-nowrap";
                });
                
                e.currentTarget.className = "status-btn px-6 py-2 rounded-full text-sm font-bold bg-surface-container-lowest dark:bg-[#111417] shadow-sm text-primary dark:text-emerald-400 transition-colors whitespace-nowrap";
                
                currentStatusFilter = e.currentTarget.innerText.trim();
                applyFilters();
            });
        });

        // ---- 2. LOGIKA DROPDOWN WAKTU & PERANGKAT ----
        filterWaktu.addEventListener('change', (e) => {
            if(e.target.value === 'kustom') {
                customDateInputs.classList.remove('hidden'); 
            } else {
                customDateInputs.classList.add('hidden'); 
                applyFilters();
            }
        });
        filterDevice.addEventListener('change', applyFilters); 
        customStart.addEventListener('change', applyFilters);
        customEnd.addEventListener('change', applyFilters);

        // ---- 3. FUNGSI PEMICU ----
        function applyFilters() {
            updatePdfLink();
            fetchRiwayatRealtime(); 
        }

        // ---- 4. UPDATE LINK PDF ----
        function updatePdfLink() {
            const pdfBtn = document.getElementById('btn-export-pdf');
            if (pdfBtn) {
                let baseUrl = "{{ route('riwayat.export_pdf') }}";
                let params = new URLSearchParams();
                
                if (currentStatusFilter !== 'Semua') params.append('status', currentStatusFilter);
                if (filterDevice.value !== 'semua') params.append('device', filterDevice.value);
                if (filterWaktu.value !== 'semua') {
                    params.append('waktu', filterWaktu.value);
                    if (filterWaktu.value === 'kustom') {
                        if (customStart.value) params.append('start', customStart.value);
                        if (customEnd.value) params.append('end', customEnd.value);
                    }
                }
                pdfBtn.href = baseUrl + '?' + params.toString();
            }
        }

        // ---- 5. AMBIL DATA DARI BACKEND BERDASARKAN FILTER ----
        function fetchRiwayatRealtime() {
            let params = new URLSearchParams();
            
            if (currentStatusFilter !== 'Semua') params.append('status', currentStatusFilter);
            if (filterDevice.value !== 'semua') params.append('device', filterDevice.value);
            if (filterWaktu.value !== 'semua') {
                params.append('waktu', filterWaktu.value);
                if (filterWaktu.value === 'kustom') {
                    if (customStart.value) params.append('start', customStart.value);
                    if (customEnd.value) params.append('end', customEnd.value);
                }
            }

            fetch('/api/get-riwayat?' + params.toString())
                .then(response => response.json())
                .then(data => {
                    let htmlRows = '';
                    let countSukses = 0;
                    let countGagal = 0;

                    if (data.length === 0) {
                        tbody.innerHTML = `<tr><td colspan="5" class="px-8 py-6 text-center text-sm text-on-surface-variant font-bold text-tertiary">Belum ada data riwayat untuk filter ini.</td></tr>`;
                        document.getElementById('stat-total').innerText = 0;
                        document.getElementById('stat-sukses').innerText = 0;
                        document.getElementById('stat-gagal').innerText = 0;
                        if (entryInfo) entryInfo.innerText = `Menampilkan 0 dari 0 entri`;
                        return;
                    }

                    data.forEach(item => {
                        let aksiColor = 'bg-secondary-container/20 text-on-secondary-container dark:bg-blue-500/10 dark:text-blue-400';
                        let aksiText = 'Otomatis';
                        
                        if(item.aksi === 'manual') {
                            aksiColor = 'bg-surface-container-highest text-on-surface-variant dark:bg-white/10 dark:text-gray-300';
                            aksiText = 'Manual';
                        } else if(item.aksi === 'smart_trigger') {
                            aksiColor = 'bg-amber-500/20 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400';
                            aksiText = 'Sensor IR';
                        }

                        let statusHtml = '';
                        if(item.status === 'berhasil') {
                            statusHtml = `<span class="flex items-center gap-2 text-xs font-bold text-primary dark:text-emerald-400"><span class="h-2 w-2 rounded-full bg-primary dark:bg-emerald-400"></span>Berhasil</span>`;
                            countSukses++;
                        } else {
                            statusHtml = `<span class="flex items-center gap-2 text-xs font-bold text-tertiary dark:text-red-400"><span class="h-2 w-2 rounded-full bg-tertiary dark:bg-red-400"></span>Gagal</span>`;
                            countGagal++;
                        }

                        let deviceName = item.device || item.device_id || 'Alat-01';

                        htmlRows += `
                            <tr class="hover:bg-surface-container-low dark:hover:bg-white/5 transition-colors">
                                <td class="px-8 py-6 text-sm font-bold text-on-surface dark:text-gray-200">${item.tanggal}</td>
                                <td class="px-8 py-6 text-sm text-on-surface-variant dark:text-gray-400">${item.waktu}</td>
                                <td class="px-8 py-6 text-sm font-bold text-on-surface-variant dark:text-gray-300">${deviceName}</td>
                                <td class="px-8 py-6">
                                    <span class="text-xs font-bold px-3 py-1 rounded-full ${aksiColor}">${aksiText}</span>
                                </td>
                                <td class="px-8 py-6">${statusHtml}</td>
                            </tr>
                        `;
                    });

                    tbody.innerHTML = htmlRows;
                    
                    document.getElementById('stat-total').innerText = data.length;
                    document.getElementById('stat-sukses').innerText = countSukses;
                    document.getElementById('stat-gagal').innerText = countGagal;

                    if (entryInfo) {
                        entryInfo.innerText = `Menampilkan ${data.length} entri`;
                    }
                })
                .catch(error => console.error('Gagal mengambil data:', error));
        }

        applyFilters();
        
        setInterval(fetchRiwayatRealtime, 3000);
    });
</script>
@endsection