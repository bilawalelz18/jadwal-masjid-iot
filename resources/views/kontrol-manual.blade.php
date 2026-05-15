@extends('layouts.app')

@section('content')
<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.5);
        box-shadow: 0 8px 32px 0 rgba(0, 108, 73, 0.04);
    }
    /* Tambahan efek glass untuk dark mode jika diperlukan ke depannya */
    .dark .glass-card {
        background: rgba(17, 20, 23, 0.7);
        border: 1px solid rgba(255, 255, 255, 0.05);
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
    }
    
    .premium-glow {
        box-shadow: 0 0 20px rgba(16, 185, 129, 0.3), 0 10px 15px -3px rgba(16, 185, 129, 0.2);
    }
    .bg-premium-gradient {
        background: linear-gradient(135deg, #006c49 0%, #10b981 100%);
    }
    @keyframes soft-pulse {
        0% { opacity: 0.4; transform: scale(0.98); }
        50% { opacity: 1; transform: scale(1.02); }
        100% { opacity: 0.4; transform: scale(0.98); }
    }
    .animate-soft-pulse {
        animation: soft-pulse 3s ease-in-out infinite;
    }

    /* Kustomisasi Scrollbar untuk Kotak Jadwal */
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: rgba(16, 185, 129, 0.2);
        border-radius: 10px;
    }
</style>

<div class="max-w-6xl mx-auto space-y-8">
    
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h2 class="text-3xl font-bold text-on-surface dark:text-gray-100 font-headline tracking-tight">Kontrol Manual</h2>
            <p class="text-secondary dark:text-gray-400 mt-1 font-medium">Aktifkan penyemprotan secara instan dari jarak jauh.</p>
        </div>
    </div>

    <div class="bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20 rounded-2xl p-4 flex items-center justify-between shadow-sm transition-colors duration-300">
        <div class="flex items-center gap-6">
            <div class="flex items-center gap-3">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-500 dark:bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500 dark:bg-emerald-400"></span>
                </span>
                <span class="text-sm font-bold text-emerald-800 dark:text-emerald-400 tracking-wide uppercase">Sistem Online</span>
            </div>
            <div class="h-4 w-[1px] bg-emerald-200 dark:bg-emerald-500/30"></div>
            <p class="text-xs font-semibold text-emerald-700 dark:text-emerald-300">ESP32 terhubung dengan stabil</p>
        </div>
        <div class="text-right">
            <p class="text-sm font-bold text-emerald-800 dark:text-emerald-400" id="live-time">00:00:00 WIB</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <div class="lg:col-span-3 flex flex-col gap-6">
            
            <div class="bg-surface-container-lowest dark:bg-[#111417] p-8 rounded-2xl shadow-sm border border-transparent dark:border-white/5 transition-colors duration-300 flex flex-col items-center justify-center text-center">
                <h3 class="text-[10px] font-bold text-outline dark:text-gray-500 uppercase tracking-widest mb-6">Sisa Cairan</h3>
                <div class="relative w-32 h-32 mb-4">
                    @php
                        $circumference = 351.85;
                        $dashoffset = $circumference - ($circumference * ($sisaPersen / 100));
                    @endphp
                    <svg class="w-full h-full transform -rotate-90">
                        <circle class="text-surface-container-high dark:text-white/5" cx="64" cy="64" fill="transparent" r="56" stroke="currentColor" stroke-width="12"></circle>
                        <circle id="circle-progress" class="text-primary-container dark:text-emerald-500 transition-all duration-1000 ease-out" cx="64" cy="64" fill="transparent" r="56" stroke="currentColor" stroke-dasharray="{{ $circumference }}" stroke-dashoffset="{{ $dashoffset }}" stroke-linecap="round" stroke-width="12"></circle>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span id="sisa-persen-text" class="text-3xl font-bold text-on-surface dark:text-gray-100 font-headline">{{ $sisaPersen }}%</span>
                    </div>
                </div>
                <p id="sisa-semprotan-text" class="text-xs text-secondary dark:text-gray-400 font-medium">±{{ $sisaSemprotan }} semprotan tersisa</p>
            </div>

            <div class="bg-surface-container-lowest dark:bg-[#111417] p-6 rounded-2xl shadow-sm border border-transparent dark:border-white/5 transition-colors duration-300 flex flex-col items-center justify-center text-center flex-1">
                <h3 class="text-[10px] font-bold text-outline dark:text-gray-500 uppercase tracking-widest mb-4">Terakhir Aktif</h3>
                <div class="w-14 h-14 rounded-full bg-primary-container/20 dark:bg-emerald-500/10 text-primary dark:text-emerald-400 flex items-center justify-center mb-3">
                    <span class="material-symbols-outlined text-2xl">history</span>
                </div>
                <p class="text-2xl font-bold text-on-surface dark:text-gray-100 font-headline" id="last-spray-time">
                    {{ $lastSpray ? \Carbon\Carbon::parse($lastSpray->created_at)->timezone('Asia/Jakarta')->format('H:i') . ' WIB' : '--:-- WIB' }}
                </p>
                <p class="text-xs text-secondary dark:text-gray-400 mt-1" id="last-spray-relative">
                    {{ $lastSpray ? \Carbon\Carbon::parse($lastSpray->created_at)->timezone('Asia/Jakarta')->diffForHumans() : 'Belum ada data' }}
                </p>
            </div>
        </div>

        <div class="lg:col-span-5 flex flex-col">
            <div class="bg-surface-container-lowest dark:bg-[#111417] p-10 rounded-2xl shadow-sm border border-transparent dark:border-white/5 transition-colors duration-300 flex-1 flex flex-col items-center justify-center relative overflow-hidden">
                
                <div class="absolute top-0 left-0 w-full h-full pointer-events-none opacity-50 dark:opacity-20 flex items-center justify-center">
                    <div class="w-64 h-64 bg-primary-container rounded-full blur-[80px]"></div>
                </div>

                <div class="text-center mb-6 relative z-10">
                    <h3 class="text-lg font-bold text-on-surface dark:text-gray-100">Semprot Manual</h3>
                    <p class="text-sm text-secondary dark:text-gray-400 mt-1">Pilih target dan tekan tombol untuk menyemprot</p>
                </div>

                <div class="relative z-10 mb-8 w-full max-w-sm mx-auto">
                    <div class="relative flex items-center">
                        <span class="material-symbols-outlined absolute left-5 text-emerald-600 dark:text-emerald-400 text-lg z-10 pointer-events-none">router</span>
                        <select id="target_device" class="w-full pl-12 pr-10 py-3.5 bg-white/80 dark:bg-[#181c20] bg-none border border-emerald-100 dark:border-white/10 rounded-2xl text-sm font-bold text-slate-700 dark:text-gray-200 focus:ring-2 focus:ring-emerald-500/50 appearance-none shadow-sm cursor-pointer transition-all outline-none">
                            <option value="all" class="bg-white dark:bg-[#181c20]">Semua Perangkat (Broadcast)</option>
                            @if(isset($devices) && $devices->count() > 0)
                                @foreach($devices as $device)
                                    <option value="{{ $device->device_id }}" class="bg-white dark:bg-[#181c20]">{{ $device->nama_perangkat }} ({{ $device->room ? $device->room->nama_ruangan : 'Area Luar' }})</option>
                                @endforeach
                            @endif
                        </select>
                        <span class="material-symbols-outlined absolute right-4 text-slate-400 pointer-events-none text-sm z-10">expand_more</span>
                    </div>
                </div>

                <div class="relative flex items-center justify-center mb-8 w-64 h-64">
                    <div class="absolute inset-0 rounded-full border border-emerald-400 dark:border-emerald-500/50 animate-ping opacity-20" style="animation-duration: 3s;"></div>
                    <div class="absolute inset-4 rounded-full border border-emerald-300 dark:border-emerald-500/30 animate-ping opacity-40" style="animation-duration: 3s; animation-delay: 0.5s;"></div>
                    <div class="absolute inset-8 rounded-full border border-emerald-200 dark:border-emerald-500/20 animate-soft-pulse"></div>
                    
                    <button onclick="openSprayModal()" class="relative group w-48 h-48 rounded-full flex items-center justify-center transition-all hover:scale-105 active:scale-95 focus:outline-none">
                        <div class="absolute inset-0 rounded-full bg-emerald-100 dark:bg-emerald-900/30 blur-xl opacity-50 group-hover:opacity-100 transition-opacity"></div>
                        
                        <div class="absolute inset-0 rounded-full border-[6px] border-emerald-50 dark:border-[#0a0c0e] bg-white dark:bg-[#181c20] shadow-[0_0_40px_rgba(16,185,129,0.3)] dark:shadow-[0_0_40px_rgba(16,185,129,0.15)] flex flex-col items-center justify-center gap-2 z-10 overflow-hidden group-hover:border-emerald-100 dark:group-hover:border-[#111417] transition-colors duration-300">
                            <div class="absolute inset-0 bg-primary/5 dark:bg-emerald-500/5 group-hover:bg-primary/10 dark:group-hover:bg-emerald-500/10 transition-colors"></div>
                            <span class="material-symbols-outlined text-5xl text-primary dark:text-emerald-400 relative z-20 transition-transform group-hover:-translate-y-1" style="font-variation-settings: 'FILL' 1;">mist</span>
                            <span class="text-sm font-extrabold text-primary dark:text-emerald-400 uppercase tracking-widest relative z-20 transition-transform group-hover:-translate-y-1">Semprot</span>
                        </div>
                    </button>
                </div>

                <div class="bg-amber-50 dark:bg-amber-500/10 border border-amber-100 dark:border-amber-500/20 px-4 py-2 rounded-full flex items-center gap-2 relative z-10 transition-colors duration-300">
                    <span class="material-symbols-outlined text-amber-600 dark:text-amber-400 text-sm">info</span>
                    <span class="text-xs font-semibold text-amber-600 dark:text-amber-400">Durasi semprotan: 5 detik</span>
                </div>
            </div>
        </div>

        <div class="lg:col-span-4 flex flex-col">
            <div class="bg-surface-container-lowest dark:bg-[#111417] p-8 rounded-2xl shadow-sm border border-transparent dark:border-white/5 transition-colors duration-300 flex-1">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-[10px] font-bold text-outline dark:text-gray-500 uppercase tracking-widest">Jadwal Otomatis</h3>
                    <a href="{{ route('jadwal.index') }}" class="text-primary dark:text-emerald-400 text-xs font-bold hover:underline">Lihat Semua</a>
                </div>

                <div id="jadwal-container" class="space-y-3 max-h-[380px] overflow-y-auto pr-2 custom-scrollbar">
                    
                    @forelse($jadwalSholats as $jadwal)
                        @php
                            $isNext = $jadwalTerdekat && $jadwal->id == $jadwalTerdekat->id;
                            $jamFormat = \Carbon\Carbon::parse($jadwal->waktu)->format('H:i');
                            $isPassed = $jadwal->waktu <= \Carbon\Carbon::now('Asia/Jakarta')->format('H:i:s');
                        @endphp

                        @if($isNext)
                            <div class="bg-primary-container/20 dark:bg-emerald-500/10 p-4 rounded-xl border border-primary/30 dark:border-emerald-500/30 flex items-center justify-between relative overflow-hidden transition-colors duration-300">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-primary dark:bg-emerald-400"></div>
                                <div>
                                    <div class="flex items-center gap-2 mb-0.5">
                                        <p class="text-xs font-extrabold text-primary dark:text-emerald-400 uppercase">{{ $jadwal->nama_sholat }}</p>
                                        <span class="bg-primary text-white dark:bg-emerald-400 dark:text-gray-900 text-[9px] px-2 py-0.5 rounded-full font-bold uppercase tracking-wider">Berikutnya</span>
                                    </div>
                                    <p class="text-lg font-bold text-primary dark:text-emerald-400 font-headline">{{ $jamFormat }}</p>
                                </div>
                                <span class="material-symbols-outlined text-primary dark:text-emerald-400 animate-pulse">schedule</span>
                            </div>
                        @else
                            <div class="bg-surface-container-low dark:bg-white/5 p-4 rounded-xl border border-slate-100 dark:border-white/5 flex items-center justify-between transition-colors duration-300">
                                <div>
                                    <p class="text-xs font-bold text-secondary dark:text-gray-400 uppercase">{{ $jadwal->nama_sholat }}</p>
                                    <p class="text-lg font-bold text-on-surface dark:text-gray-100 font-headline">{{ $jamFormat }}</p>
                                </div>
                                @if($isPassed)
                                    <span class="material-symbols-outlined text-blue-500 dark:text-blue-400">done_all</span>
                                @else
                                    <span class="material-symbols-outlined text-orange-500 dark:text-orange-400">schedule</span>
                                @endif
                            </div>
                        @endif
                    @empty
                        <div class="text-center py-8">
                            <p class="text-sm text-gray-500">Belum ada jadwal tersimpan di sistem.</p>
                        </div>
                    @endforelse

                </div>
            </div>
        </div>

    </div>
</div>

<div id="sprayModal" class="hidden fixed inset-0 bg-black/40 dark:bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center transition-opacity duration-300 opacity-0">
    <div class="bg-[#181c20] rounded-3xl shadow-2xl max-w-sm w-full mx-4 p-8 text-center border border-white/5 transform scale-95 transition-all duration-300" id="modalContent">
        <div class="flex justify-center mb-6">
            <div class="w-16 h-16 bg-[#0f211b] rounded-full flex items-center justify-center">
                <span class="material-symbols-outlined text-3xl text-emerald-400" style="font-variation-settings: 'FILL' 1;">mist</span>
            </div>
        </div>
        
        <h2 class="text-2xl font-bold font-headline text-gray-100 mb-3">Konfirmasi Penyemprotan</h2>
        
        <p class="text-sm text-gray-400 mb-2">Target: <strong id="modal-device-name" class="text-emerald-400">Semua Perangkat</strong></p>
        
        <p class="text-gray-400 text-sm mb-8 leading-relaxed">Perintah akan langsung dikirim ke perangkat IoT. Apakah Anda yakin ingin menyemprotkan sekarang?</p>
        
        <div class="flex gap-4">
            <button type="button" onclick="closeSprayModal()" class="flex-1 py-4 rounded-full bg-[#202428] hover:bg-[#2a2f34] text-gray-200 font-bold transition-colors">
                Batal
            </button>
            <button type="button" onclick="executeSpray()" class="flex-1 py-4 rounded-full bg-emerald-500 hover:bg-emerald-600 text-[#0a0c0e] font-bold flex items-center justify-center gap-2 transition-colors">
                <span class="material-symbols-outlined text-lg">mist</span>
                Semprot
            </button>
        </div>
    </div>
</div>

<script>
    // 1. Live Clock Update
    function updateTime() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        document.getElementById('live-time').textContent = `${hours}:${minutes}:${seconds} WIB`;
    }
    setInterval(updateTime, 1000);
    updateTime();

    // 2. Modal Logic
    function openSprayModal() {
        const modal = document.getElementById('sprayModal');
        const content = document.getElementById('modalContent');
        const selectElement = document.getElementById('target_device');
        const selectedText = selectElement.options[selectElement.selectedIndex].text;
        
        document.getElementById('modal-device-name').textContent = selectedText;

        modal.classList.remove('hidden');
        void modal.offsetWidth; // Trigger reflow
        modal.classList.remove('opacity-0');
        content.classList.remove('scale-95');
        content.classList.add('scale-100');
    }

    function closeSprayModal() {
        const modal = document.getElementById('sprayModal');
        const content = document.getElementById('modalContent');
        
        modal.classList.add('opacity-0');
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }
    
    // 3. Eksekusi Semprot ke Backend
    function executeSpray() {
        closeSprayModal();
        
        const selectElement = document.getElementById('target_device');
        const deviceId = selectElement.value;
        const deviceName = selectElement.options[selectElement.selectedIndex].text;

        fetch('{{ route('kontrol.spray') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ target: deviceId })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                // Update tampilan UI (Fake Realtime)
                const jamSekarang = new Date().getHours().toString().padStart(2, '0') + ':' + new Date().getMinutes().toString().padStart(2, '0');
                document.getElementById('last-spray-time').textContent = jamSekarang + ' WIB';
                document.getElementById('last-spray-relative').textContent = 'Baru saja';
                
                showSuccessNotification(`Perintah penyemprotan berhasil dikirim ke ${deviceName}!`);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghubungi server.');
        });
    }
    
    // 4. Notifikasi
    function showSuccessNotification(message) {
        const notification = document.createElement('div');
        notification.className = 'fixed top-24 right-8 z-50 bg-surface-container-lowest dark:bg-[#111417] rounded-2xl shadow-2xl border border-primary/20 dark:border-emerald-500/20 p-5 transition-colors duration-300';
        notification.innerHTML = `
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-primary dark:text-emerald-400 text-2xl">check_circle</span>
                <p class="text-sm font-semibold text-on-surface dark:text-gray-100">${message}</p>
            </div>
        `;
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    }
    
    document.getElementById('sprayModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeSprayModal();
    });

    // 5. AJAX DROPDOWN CHANGE LISTENER (LOGIKA RUANGAN)
    document.getElementById('target_device').addEventListener('change', function() {
        const deviceId = this.value;
        
        // Tampilkan efek loading pada kotak jadwal
        document.getElementById('jadwal-container').innerHTML = '<div class="text-center py-8"><span class="material-symbols-outlined animate-spin text-emerald-500 text-3xl">refresh</span><p class="text-xs text-gray-500 mt-2">Memuat data...</p></div>';

        // Tembak URL API Data Spesifik Perangkat
        fetch(`{{ route('kontrol.device_data') }}?device_id=${deviceId}`)
            .then(response => response.json())
            .then(data => {
                // --- Update SVG Sisa Cairan ---
                const circumference = 351.85;
                const dashoffset = circumference - (circumference * (data.sisaPersen / 100));
                document.getElementById('circle-progress').style.strokeDashoffset = dashoffset;
                document.getElementById('sisa-persen-text').textContent = data.sisaPersen + '%';
                document.getElementById('sisa-semprotan-text').textContent = '±' + data.sisaSemprotan + ' semprotan tersisa';

                // --- Update Teks Terakhir Aktif ---
                document.getElementById('last-spray-time').textContent = data.lastSprayTime;
                document.getElementById('last-spray-relative').textContent = data.lastSprayRelative;

                // --- Update Daftar Jadwal ---
                let jadwalHtml = '';
                if(data.jadwals.length === 0) {
                    jadwalHtml = '<div class="text-center py-8"><p class="text-sm text-gray-500">Belum ada jadwal tersimpan untuk ruangan ini.</p></div>';
                } else {
                    data.jadwals.forEach(j => {
                        if(j.is_next) {
                            jadwalHtml += `
                            <div class="bg-primary-container/20 dark:bg-emerald-500/10 p-4 rounded-xl border border-primary/30 dark:border-emerald-500/30 flex items-center justify-between relative overflow-hidden transition-colors duration-300">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-primary dark:bg-emerald-400"></div>
                                <div>
                                    <div class="flex items-center gap-2 mb-0.5">
                                        <p class="text-xs font-extrabold text-primary dark:text-emerald-400 uppercase">${j.nama_sholat}</p>
                                        <span class="bg-primary text-white dark:bg-emerald-400 dark:text-gray-900 text-[9px] px-2 py-0.5 rounded-full font-bold uppercase tracking-wider">Berikutnya</span>
                                    </div>
                                    <p class="text-lg font-bold text-primary dark:text-emerald-400 font-headline">${j.waktu}</p>
                                </div>
                                <span class="material-symbols-outlined text-primary dark:text-emerald-400 animate-pulse">schedule</span>
                            </div>`;
                        } else {
                            const icon = j.is_passed 
                                ? '<span class="material-symbols-outlined text-blue-500 dark:text-blue-400">done_all</span>'
                                : '<span class="material-symbols-outlined text-orange-500 dark:text-orange-400">schedule</span>';
                                
                            jadwalHtml += `
                            <div class="bg-surface-container-low dark:bg-white/5 p-4 rounded-xl border border-slate-100 dark:border-white/5 flex items-center justify-between transition-colors duration-300">
                                <div>
                                    <p class="text-xs font-bold text-secondary dark:text-gray-400 uppercase">${j.nama_sholat}</p>
                                    <p class="text-lg font-bold text-on-surface dark:text-gray-100 font-headline">${j.waktu}</p>
                                </div>
                                ${icon}
                            </div>`;
                        }
                    });
                }
                document.getElementById('jadwal-container').innerHTML = jadwalHtml;
            })
            .catch(error => console.error('Error fetching device data:', error));
    });
</script>
@endsection