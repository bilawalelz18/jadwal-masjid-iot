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
            
            <!-- 1. FILTER WAKTU -->
            <div class="relative flex-1 w-full lg:w-auto flex flex-col">
                <div class="relative w-full">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant dark:text-gray-400 material-symbols-outlined">calendar_today</span>
                    <select id="filter-waktu" class="w-full pl-12 pr-4 py-3 bg-surface-container-lowest dark:bg-[#181c20] rounded-full border-none text-sm font-semibold text-on-surface dark:text-gray-200 shadow-sm dark:shadow-none focus:ring-2 focus:ring-emerald-500/50 appearance-none transition-colors duration-300">
                        <option value="semua">Semua Waktu</option>
                        <option value="7_hari">7 Hari Terakhir</option>
                        <option value="30_hari">30 Hari Terakhir</option>
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

            <!-- 2. FILTER PERANGKAT -->
            <div class="relative flex-1 w-full lg:w-auto flex flex-col">
                <div class="relative w-full">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant dark:text-gray-400 material-symbols-outlined">router</span>
                    <select id="filter-device" class="w-full pl-12 pr-4 py-3 bg-surface-container-lowest dark:bg-[#181c20] rounded-full border-none text-sm font-semibold text-on-surface dark:text-gray-200 shadow-sm dark:shadow-none focus:ring-2 focus:ring-emerald-500/50 appearance-none transition-colors duration-300">
                        <option value="semua">Semua Perangkat</option>
                        <!-- Opsi perangkat akan diisi otomatis oleh Javascript -->
                    </select>
                </div>
            </div>
            
            <!-- 3. FILTER STATUS -->
            <div class="flex bg-surface-container-highest dark:bg-[#181c20] p-1 rounded-full w-full lg:w-auto overflow-x-auto" id="filter-status-container">
                <button type="button" class="status-btn px-6 py-2 rounded-full text-sm font-bold bg-surface-container-lowest dark:bg-[#111417] shadow-sm text-primary dark:text-emerald-400 transition-colors whitespace-nowrap">Semua</button>
                <button type="button" class="status-btn px-6 py-2 rounded-full text-sm font-bold text-on-surface-variant dark:text-gray-500 hover:text-on-surface dark:hover:text-gray-300 transition-colors whitespace-nowrap">Berhasil</button>
                <button type="button" class="status-btn px-6 py-2 rounded-full text-sm font-bold text-on-surface-variant dark:text-gray-500 hover:text-on-surface dark:hover:text-gray-300 transition-colors whitespace-nowrap">Gagal</button>
            </div>
        </div>
        
        <!-- TOMBOL EKSPOR PDF SEKARANG MENGARAH KE ROUTE BACKEND -->
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
                   <!-- Nanti dikosongkan karena data akan diisi oleh Javascript -->
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

        // ---- 1. LOGIKA TOMBOL STATUS (SEMUA/BERHASIL/GAGAL) ----
        statusBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                // Reset semua tombol ke warna abu-abu tidak aktif
                statusBtns.forEach(b => {
                    b.className = "status-btn px-6 py-2 rounded-full text-sm font-bold text-on-surface-variant dark:text-gray-500 hover:text-on-surface dark:hover:text-gray-300 transition-colors whitespace-nowrap";
                });
                
                // Beri warna utama pada tombol yang diklik
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

        function parseIndoDate(dateStr) {
            const months = {
                'Jan': 0, 'Feb': 1, 'Mar': 2, 'Apr': 3, 'Mei': 4, 'Jun': 5,
                'Jul': 6, 'Agu': 7, 'Sep': 8, 'Okt': 9, 'Nov': 10, 'Des': 11
            };
            const parts = dateStr.trim().split(' ');
            if(parts.length === 3) {
                return new Date(parseInt(parts[2]), months[parts[1]], parseInt(parts[0]));
            }
            return new Date(0); 
        }

        // ---- 3. FUNGSI UTAMA PENYARINGAN TABEL ----
        function applyFilters() {
            const trs = tbody.querySelectorAll('tr');
            const timeFilter = filterWaktu.value;
            const deviceFilterValue = filterDevice.value; 
            const now = new Date();
            const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());

            let visibleCount = 0;
            const totalCount = trs.length;

            trs.forEach(tr => {
                if(tr.cells.length < 5) return;

                const dateStr = tr.cells[0].innerText.trim();
                const deviceStr = tr.cells[2].innerText.trim(); 
                const statusStr = tr.cells[4].innerText.trim(); 

                // Cek Filter Status
                let statusMatch = true;
                if (currentStatusFilter !== 'Semua') {
                    statusMatch = statusStr.includes(currentStatusFilter);
                }

                // Cek Filter Perangkat
                let deviceMatch = true;
                if (deviceFilterValue !== 'semua') {
                    deviceMatch = (deviceStr === deviceFilterValue);
                }

                // Cek Filter Waktu
                let timeMatch = true;
                const rowDate = parseIndoDate(dateStr);

                if (timeFilter === '7_hari') {
                    const past = new Date(today);
                    past.setDate(today.getDate() - 7);
                    timeMatch = rowDate >= past && rowDate <= now;
                } else if (timeFilter === '30_hari') {
                    const past = new Date(today);
                    past.setDate(today.getDate() - 30);
                    timeMatch = rowDate >= past && rowDate <= now;
                } else if (timeFilter === 'bulan_ini') {
                    timeMatch = rowDate.getMonth() === now.getMonth() && rowDate.getFullYear() === now.getFullYear();
                } else if (timeFilter === 'kustom') {
                    const s = customStart.value ? new Date(customStart.value) : null;
                    const e = customEnd.value ? new Date(customEnd.value) : null;
                    if (s) s.setHours(0,0,0,0);
                    if (e) e.setHours(23,59,59,999);
                    
                    if (s && rowDate < s) timeMatch = false;
                    if (e && rowDate > e) timeMatch = false;
                }

                // Tampilkan jika lolos semua filter
                if (statusMatch && timeMatch && deviceMatch) {
                    tr.style.display = '';
                    visibleCount++;
                } else {
                    tr.style.display = 'none';
                }
            });

            if (entryInfo) {
                entryInfo.innerText = `Menampilkan ${visibleCount} dari ${totalCount} entri`;
            }

            // =========================================================
            // UPDATE OTOMATIS LINK EKSPOR PDF AGAR SESUAI DENGAN FILTER
            // =========================================================
            const pdfBtn = document.getElementById('btn-export-pdf');
            if (pdfBtn) {
                let baseUrl = "{{ route('riwayat.export_pdf') }}";
                let params = new URLSearchParams();
                
                // Masukkan parameter ke dalam link
                if (currentStatusFilter !== 'Semua') params.append('status', currentStatusFilter);
                if (deviceFilterValue !== 'semua') params.append('device', deviceFilterValue);
                if (timeFilter !== 'semua') {
                    params.append('waktu', timeFilter);
                    if (timeFilter === 'kustom') {
                        if (customStart.value) params.append('start', customStart.value);
                        if (customEnd.value) params.append('end', customEnd.value);
                    }
                }
                
                // Terapkan ke tombol
                pdfBtn.href = baseUrl + '?' + params.toString();
            }
        }

        // ==============================================
        // SCRIPT REALTIME DATA DARI SERVER (AJAX POLLING)
        // ==============================================
        function fetchRiwayatRealtime() {
            fetch('/api/get-riwayat')
                .then(response => response.json())
                .then(data => {
                    let htmlRows = '';
                    let countSukses = 0;
                    let countGagal = 0;

                    if (data.length === 0) {
                        tbody.innerHTML = `<tr><td colspan="5" class="px-8 py-6 text-center text-sm text-on-surface-variant">Belum ada data riwayat penyemprotan.</td></tr>`;
                        return;
                    }

                    // --- UPDATE ISI DROPDOWN FILTER PERANGKAT SECARA OTOMATIS ---
                    const currentDeviceSelection = filterDevice.value;
                    let uniqueDevices = [...new Set(data.map(item => item.device || item.device_id || 'Alat-01'))];
                    let deviceOptions = '<option value="semua">Semua Perangkat</option>';
                    uniqueDevices.forEach(dev => {
                        deviceOptions += `<option value="${dev}" ${currentDeviceSelection === dev ? 'selected' : ''}>${dev}</option>`;
                    });
                    filterDevice.innerHTML = deviceOptions;
                    // -----------------------------------------------------------

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

                    applyFilters();
                })
                .catch(error => console.error('Gagal mengambil data:', error));
        }

        fetchRiwayatRealtime();
        setInterval(fetchRiwayatRealtime, 3000);
    });
</script>
@endsection