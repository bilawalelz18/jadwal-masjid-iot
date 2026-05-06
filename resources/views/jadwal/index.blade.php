@extends('layouts.app')

@section('content')
    <div class="pt-24 px-10 pb-12">
        <div class="flex flex-col xl:flex-row xl:items-center justify-between mb-10 gap-6">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-on-surface dark:text-gray-100 mb-2 font-headline transition-colors duration-300">Manajemen Jadwal Sholat</h1>
                <p class="text-secondary dark:text-gray-400 font-body transition-colors duration-300">Konfigurasi waktu penyemprotan otomatis berdasarkan jadwal sholat harian.</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-4">
                <form method="GET" action="{{ route('jadwal.index') }}" class="flex items-center">
                    @if(request('all'))
                        <input type="hidden" name="all" value="1">
                    @endif
                    <div class="relative flex items-center">
                        <span class="material-symbols-outlined absolute left-4 text-emerald-600 dark:text-emerald-400 text-sm">meeting_room</span>
                        <select name="room_id" onchange="this.form.submit()" class="pl-10 pr-10 py-3 rounded-full bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-sm font-bold text-slate-700 dark:text-gray-200 focus:ring-2 focus:ring-emerald-500/50 appearance-none shadow-sm cursor-pointer transition-colors outline-none">
                            
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}" {{ $roomId == $room->id ? 'selected' : '' }} class="bg-white dark:bg-[#181c20] text-slate-900 dark:text-gray-100">{{ $room->nama_ruangan }}</option>
                            @endforeach
                        </select>
                        <span class="material-symbols-outlined absolute right-3 text-slate-400 pointer-events-none text-sm">expand_more</span>
                    </div>
                </form>

                <button onclick="showDeleteAllModal()" class="bg-red-500 dark:bg-red-600 hover:bg-red-600 dark:hover:bg-red-700 text-white px-6 py-3 rounded-full font-bold shadow-lg transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg">delete_sweep</span>
                    <span class="hidden sm:inline">Hapus Semua</span>
                </button>
                <a href="{{ route('jadwal.create') }}" class="bg-primary dark:bg-emerald-600 hover:bg-opacity-90 dark:hover:bg-emerald-500 text-white px-8 py-3 rounded-full font-bold shadow-lg shadow-emerald-900/10 transition-all flex items-center gap-2 w-fit">
                    <span class="material-symbols-outlined text-xl">add</span>
                    <span class="hidden sm:inline">Tambah Jadwal</span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12 lg:col-span-4 rounded-xl bg-surface-container-low dark:bg-[#111417] border border-transparent dark:border-white/5 transition-colors duration-300 p-8 flex flex-col">
                <div class="mb-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-primary-container/20 dark:bg-emerald-500/20 text-on-primary-container dark:text-emerald-300 text-[10px] font-bold tracking-widest uppercase mb-4">Live Info</span>
                    <h2 class="text-2xl font-bold leading-tight text-on-surface dark:text-gray-100 transition-colors duration-300">Sinkronisasi Waktu Server</h2>
                    <div class="mt-6 p-4 rounded-full bg-white/50 dark:bg-white/5 border border-emerald-100 dark:border-emerald-500/20 transition-colors duration-300">
                        <p class="text-xs font-bold text-emerald-800 dark:text-emerald-400 uppercase tracking-wider mb-1 px-4">Penyemprotan Berikutnya</p>
                        <div class="flex items-center gap-2 text-primary dark:text-emerald-400 font-bold px-4 pb-1">
                            <span class="material-symbols-outlined text-sm">event_repeat</span>
                            <span class="text-sm">Selanjutnya: Ashar - 15:10 WIB</span>
                        </div>
                    </div>
                </div>
                <div class="mt-4 space-y-4">
                    <div class="flex items-end gap-2">
                        <span class="text-5xl font-bold text-primary dark:text-emerald-400" id="jadwal-realtime-clock">12:45</span>
                        <span class="text-xl text-secondary dark:text-gray-400 pb-1 font-semibold">WIB</span>
                    </div>
                    <p class="text-sm text-secondary dark:text-gray-400 font-body transition-colors duration-300" id="last-updated">Terakhir diperbarui: baru saja melalui API Kemenag Pusat.</p>
                    <form action="{{ route('jadwal.sync') }}" method="POST" class="mt-4 w-full" onsubmit="saveLastSyncTime()">
                        @csrf
                        <input type="hidden" name="room_id" value="{{ $roomId }}">
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 border border-primary/30 dark:border-emerald-500/30 hover:border-primary/60 dark:hover:border-emerald-500/60 hover:bg-primary/5 dark:hover:bg-emerald-500/10 text-primary dark:text-emerald-400 text-xs font-bold rounded-full transition-all duration-200 font-['Manrope'] tracking-wide">
                            <span class="material-symbols-outlined text-sm">sync</span>
                            <span>Sinkronisasi API</span>
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-span-12 lg:col-span-8 bg-surface-container-lowest dark:bg-[#111417] rounded-xl shadow-[0px_20px_40px_rgba(25,28,30,0.06)] border border-transparent dark:border-white/5 transition-colors duration-300 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-surface-container-high/30 dark:bg-white/5 transition-colors duration-300">
                                <th class="px-8 py-6 text-[11px] font-bold text-secondary dark:text-gray-400 tracking-widest uppercase font-label">Tanggal</th>
                                <th class="px-8 py-6 text-[11px] font-bold text-secondary dark:text-gray-400 tracking-widest uppercase font-label">Waktu Sholat</th>
                                <th class="px-8 py-6 text-[11px] font-bold text-secondary dark:text-gray-400 tracking-widest uppercase font-label">Ruangan</th>
                                <th class="px-8 py-6 text-[11px] font-bold text-secondary dark:text-gray-400 tracking-widest uppercase font-label">Jam Penyemprotan</th>
                                <th class="px-8 py-6 text-[11px] font-bold text-secondary dark:text-gray-400 tracking-widest uppercase font-label">Input</th>
                                <th class="px-8 py-6 text-[11px] font-bold text-secondary dark:text-gray-400 tracking-widest uppercase font-label text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-transparent">
                            @php
                                $iconMap = [
                                    'Tahajud' => 'star_half',
                                    'Subuh' => 'wb_twilight',
                                    'Dhuha' => 'light_mode',
                                    'Dzuhur' => 'sunny',
                                    'Ashar' => 'partly_cloudy_day',
                                    'Maghrib' => 'nights_stay',
                                    'Isya' => 'bedtime',
                                ];
                            @endphp

                            @forelse($jadwals as $item)
                                <tr class="hover:bg-surface-container-low/50 dark:hover:bg-white/5 transition-colors duration-300">
                                    <td class="px-8 py-6 text-sm font-semibold text-secondary dark:text-gray-300">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}</td>
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-full bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                                                <span class="material-symbols-outlined">{{ $iconMap[$item->nama_sholat] ?? 'schedule' }}</span>
                                            </div>
                                            <span class="font-bold text-on-surface dark:text-gray-100">{{ $item->nama_sholat }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-sm font-semibold text-secondary dark:text-gray-300">{{ $item->room ? $item->room->nama_ruangan : 'Semua Ruangan' }}</td>
                                    <td class="px-8 py-6 font-semibold text-on-surface dark:text-gray-100">{{ \Carbon\Carbon::parse($item->waktu)->format('H:i') }} WIB</td>
                                    <td class="px-8 py-6">
                                        <span class="px-4 py-1.5 rounded-full {{ $item->is_api ? 'bg-primary-container/10 dark:bg-emerald-500/10 text-primary dark:text-emerald-400' : 'bg-secondary-container/30 dark:bg-gray-800 text-secondary dark:text-gray-300' }} text-xs font-bold">{{ $item->is_api ? 'API' : 'Manual' }}</span>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('jadwal.edit', $item->id) }}" class="p-2 text-secondary dark:text-gray-500 hover:text-primary dark:hover:text-emerald-400 transition-colors">
                                                <span class="material-symbols-outlined">edit</span>
                                            </a>
                                            <button onclick="showDeleteModal({{ $item->id }})" type="button" class="p-2 text-secondary dark:text-gray-500 hover:text-error dark:hover:text-red-400 transition-colors">
                                                <span class="material-symbols-outlined">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-8 py-10 text-center text-slate-500 dark:text-gray-500 font-medium">
                                        Belum ada jadwal hari ini. Silakan tarik API atau tambah manual.
                                    </td>
                                </tr>
                            @endforelse

                            @if(isset($showAll) && !$showAll && $hasOtherDate)
                                <tr>
                                    <td colspan="6" class="px-8 py-6 text-right">
                                        <a href="{{ route('jadwal.index', ['all' => 1, 'room_id' => $roomId]) }}" class="inline-flex items-center justify-center rounded-full bg-primary dark:bg-emerald-600 text-white px-5 py-2 text-sm font-bold hover:bg-emerald-700 dark:hover:bg-emerald-500 transition-colors">
                                            Lihat semua jadwal
                                        </a>
                                    </td>
                                </tr>
                            @elseif(isset($showAll) && $showAll)
                                <tr>
                                    <td colspan="6" class="px-8 py-6 text-right">
                                        <a href="{{ route('jadwal.index', ['room_id' => $roomId]) }}" class="inline-flex items-center justify-center rounded-full bg-surface-container-high dark:bg-white/10 text-secondary dark:text-gray-300 px-5 py-2 text-sm font-bold hover:bg-surface-container-low dark:hover:bg-white/20 transition-colors">
                                            Kembali ke jadwal hari ini
                                        </a>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="deleteModal" class="hidden fixed inset-0 bg-black/40 dark:bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center">
        <div class="bg-surface-container-lowest dark:bg-[#111417] border border-transparent dark:border-white/5 rounded-3xl shadow-2xl max-w-md w-full mx-4 p-8">
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 bg-error/10 dark:bg-red-500/10 rounded-full flex items-center justify-center">
                    <span class="material-symbols-outlined text-4xl text-error dark:text-red-400">delete_outline</span>
                </div>
            </div>
            <h2 class="text-2xl font-bold font-headline text-on-surface dark:text-gray-100 text-center mb-3">Hapus Jadwal</h2>
            <p class="text-on-surface-variant dark:text-gray-400 text-center mb-8 text-sm">Apakah Anda yakin ingin menghapus jadwal ini? Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex gap-3">
                <button onclick="closeDeleteModal()" class="flex-1 px-4 py-3 rounded-full border-2 border-outline/30 dark:border-white/10 text-on-surface dark:text-gray-300 font-bold hover:bg-surface-container dark:hover:bg-white/5 transition-colors">
                    Batal
                </button>
                <button onclick="executeDelete()" class="flex-1 px-4 py-3 rounded-full bg-error dark:bg-red-600 text-white font-bold hover:opacity-90 transition-opacity flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-lg">delete</span>
                    Hapus
                </button>
            </div>
        </div>
    </div>

    <div id="deleteAllModal" class="hidden fixed inset-0 bg-black/40 dark:bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center">
        <div class="bg-surface-container-lowest dark:bg-[#111417] border border-transparent dark:border-white/5 rounded-3xl shadow-2xl max-w-md w-full mx-4 p-8">
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 bg-error/10 dark:bg-red-500/10 rounded-full flex items-center justify-center">
                    <span class="material-symbols-outlined text-4xl text-error dark:text-red-400">delete_sweep</span>
                </div>
            </div>
            <h2 class="text-2xl font-bold font-headline text-on-surface dark:text-gray-100 text-center mb-3">Hapus Semua Jadwal</h2>
            <p class="text-on-surface-variant dark:text-gray-400 text-center mb-8 text-sm">Apakah Anda yakin ingin menghapus semua jadwal di ruangan ini? Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex gap-3">
                <button onclick="closeDeleteAllModal()" class="flex-1 px-4 py-3 rounded-full border-2 border-outline/30 dark:border-white/10 text-on-surface dark:text-gray-300 font-bold hover:bg-surface-container dark:hover:bg-white/5 transition-colors">
                    Batal
                </button>
                <button onclick="executeDeleteAll()" class="flex-1 px-4 py-3 rounded-full bg-error dark:bg-red-600 text-white font-bold hover:opacity-90 transition-opacity flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-lg">delete_sweep</span>
                    Hapus Semua
                </button>
            </div>
        </div>
    </div>

    <script>
        let deleteItemId = null;
        let deleteForm = null;

        function getCsrfToken() {
            return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        }

        function showDeleteModal(id) {
            deleteItemId = id;
            
            // Buat form langsung saat modal dibuka
            deleteForm = document.createElement('form');
            deleteForm.method = 'POST';
            deleteForm.action = `/jadwal/${id}`;
            deleteForm.style.display = 'none';
            deleteForm.innerHTML = `
                <input type="hidden" name="_token" value="${getCsrfToken()}">
                <input type="hidden" name="_method" value="DELETE">
            `;
            document.body.appendChild(deleteForm);
            
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            if (deleteForm && deleteForm.parentNode) {
                deleteForm.parentNode.removeChild(deleteForm);
            }
            deleteItemId = null;
            deleteForm = null;
        }

        function executeDelete() {
            if (deleteForm) {
                deleteForm.submit();
            }
        }

        function showDeleteAllModal() {
            document.getElementById('deleteAllModal').classList.remove('hidden');
        }

        function closeDeleteAllModal() {
            document.getElementById('deleteAllModal').classList.add('hidden');
        }

       function executeDeleteAll() {
            closeDeleteAllModal();
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("jadwal.destroyAll") }}';
            form.style.display = 'none';
            // TAMBAHKAN INPUT ROOM ID DI BAWAH INI
            form.innerHTML = `
                <input type="hidden" name="_token" value="${getCsrfToken()}">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="room_id" value="{{ $roomId }}">
            `;
            document.body.appendChild(form);
            form.submit();
        }

        // Close modals when clicking outside
        document.getElementById('deleteModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeDeleteModal();
        });

        document.getElementById('deleteAllModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeDeleteAllModal();
        });

        // Real-time clock update for jadwal page
        function updateJadwalClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const clockElement = document.getElementById('jadwal-realtime-clock');
            if (clockElement) {
                clockElement.textContent = `${hours}:${minutes}`;
            }
        }

        // Save last sync time to localStorage
        function saveLastSyncTime() {
            const now = new Date().getTime();
            localStorage.setItem('last_jadwal_sync_time', now);
        }

        // Get last sync time from localStorage or use page load time
        function getLastSyncTime() {
            const savedTime = localStorage.getItem('last_jadwal_sync_time');
            if (savedTime) {
                return parseInt(savedTime);
            }
            return new Date().getTime();
        }

        // Real-time last updated time (Konversi format human-readable)
        function updateLastUpdated() {
            const now = new Date().getTime();
            const lastSyncTime = getLastSyncTime();
            const diffMs = now - lastSyncTime;
            const diffMinutes = Math.floor(diffMs / (1000 * 60));
            const element = document.getElementById('last-updated');
            
            if (element) {
                let timeString = '';
                if (diffMinutes < 1) {
                    timeString = 'baru saja';
                } else if (diffMinutes < 60) {
                    timeString = `${diffMinutes} menit yang lalu`;
                } else if (diffMinutes < 1440) {
                    const diffHours = Math.floor(diffMinutes / 60);
                    timeString = `${diffHours} jam yang lalu`;
                } else {
                    const diffDays = Math.floor(diffMinutes / 1440);
                    timeString = `${diffDays} hari yang lalu`;
                }
                
                element.textContent = `Terakhir diperbarui: ${timeString} melalui API Kemenag Pusat.`;
            }
        }

        // Update every second for both clock and last updated
        setInterval(updateJadwalClock, 1000);
        setInterval(updateLastUpdated, 1000);
        // Initial updates
        updateJadwalClock();
        updateLastUpdated();
    </script>
@endsection