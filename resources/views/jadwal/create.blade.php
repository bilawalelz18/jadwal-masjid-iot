@extends('layouts.app')

@section('content')
    <style>
        .bg-primary-gradient {
            background: linear-gradient(135deg, #006c49 0%, #10b981 100%);
        }
        /* Perbaikan Ikon Jam & Tanggal untuk Mode Gelap */
        .dark input[type="time"],
        .dark input[type="date"] {
            color-scheme: dark;
        }
    </style>

    <div class="max-w-6xl mx-auto">
        <header class="flex justify-between items-end mb-10">
            <div>
                <h2 class="text-3xl font-headline font-semibold text-on-surface dark:text-gray-100 transition-colors duration-300">Manajemen Jadwal Sholat</h2>
                <p class="text-on-surface-variant dark:text-gray-400 mt-2 transition-colors duration-300">Konfigurasi waktu penyemprotan otomatis berdasarkan jadwal ibadah.</p>
            </div>
            <button type="button" class="bg-primary-gradient text-white px-8 py-3 rounded-full flex items-center gap-2 font-semibold shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all">
                <span class="material-symbols-outlined">add</span>
                Tambah Jadwal
            </button>
        </header>

        <div class="bg-surface-container-low dark:bg-[#111417] border border-transparent dark:border-white/5 rounded-xl overflow-hidden p-1 transition-colors duration-300">
            <div class="bg-surface-container-lowest dark:bg-[#181c20] rounded-lg overflow-hidden transition-colors duration-300">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-high/30 dark:bg-white/5 transition-colors duration-300">
                            <th class="px-6 py-5 text-xs font-label uppercase tracking-widest text-on-surface-variant dark:text-gray-400">Nama Sholat</th>
                            <th class="px-6 py-5 text-xs font-label uppercase tracking-widest text-on-surface-variant dark:text-gray-400">Waktu Sholat</th>
                            <th class="px-6 py-5 text-xs font-label uppercase tracking-widest text-on-surface-variant dark:text-gray-400">Penyemprotan</th>
                            <th class="px-6 py-5 text-xs font-label uppercase tracking-widest text-on-surface-variant dark:text-gray-400">Status</th>
                            <th class="px-6 py-5 text-xs font-label uppercase tracking-widest text-on-surface-variant dark:text-gray-400">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-transparent dark:divide-white/5 transition-colors duration-300">
                        <tr class="hover:bg-surface-container-low/50 dark:hover:bg-white/5 transition-colors">
                            <td class="px-6 py-6 font-semibold text-on-surface dark:text-gray-200">Subuh</td>
                            <td class="px-6 py-6 text-on-surface-variant dark:text-gray-400">04:32 WIB</td>
                            <td class="px-6 py-6 font-medium text-emerald-700 dark:text-emerald-400">04:15 WIB</td>
                            <td class="px-6 py-6">
                                <span class="px-3 py-1 rounded-full bg-primary-container/20 dark:bg-emerald-500/10 text-on-primary-container dark:text-emerald-300 text-[11px] font-bold uppercase tracking-wider">Aktif</span>
                            </td>
                            <td class="px-6 py-6">
                                <button class="text-outline dark:text-gray-500 hover:text-emerald-700 dark:hover:text-emerald-400 transition-colors"><span class="material-symbols-outlined">more_vert</span></button>
                            </td>
                        </tr>
                        <tr class="bg-surface-container-low/30 dark:bg-white/[0.02] hover:bg-surface-container-low/50 dark:hover:bg-white/5 transition-colors">
                            <td class="px-6 py-6 font-semibold text-on-surface dark:text-gray-200">Dzuhur</td>
                            <td class="px-6 py-6 text-on-surface-variant dark:text-gray-400">12:05 WIB</td>
                            <td class="px-6 py-6 font-medium text-emerald-700 dark:text-emerald-400">11:50 WIB</td>
                            <td class="px-6 py-6">
                                <span class="px-3 py-1 rounded-full bg-primary-container/20 dark:bg-emerald-500/10 text-on-primary-container dark:text-emerald-300 text-[11px] font-bold uppercase tracking-wider">Aktif</span>
                            </td>
                            <td class="px-6 py-6">
                                <button class="text-outline dark:text-gray-500 hover:text-emerald-700 dark:hover:text-emerald-400 transition-colors"><span class="material-symbols-outlined">more_vert</span></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="fixed inset-0 z-[60] flex items-center justify-center bg-on-surface/40 dark:bg-black/60 backdrop-blur-md transition-colors duration-300">
        <div class="w-full max-w-xl bg-surface-container-lowest dark:bg-[#111417] border border-transparent dark:border-white/5 rounded-xl shadow-[0px_20px_40px_rgba(25,28,30,0.06)] overflow-hidden scale-100 transition-all duration-300">
            <form action="{{ route('jadwal.store') }}" method="POST" onsubmit="return validateCreateDateRange()">
                @csrf
                <div class="px-8 pt-8 pb-4 flex justify-between items-center border-b border-transparent dark:border-white/5 transition-colors">
                <h3 class="text-2xl font-headline font-semibold text-on-surface dark:text-gray-100">Tambah Jadwal Sholat</h3>
                <button type="button" onclick="window.location='{{ route('jadwal.index') }}'" class="text-outline dark:text-gray-500 hover:text-on-surface dark:hover:text-gray-300 transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div class="px-8 py-6 space-y-6">
                
                <div class="space-y-2">
                    <label class="text-xs font-label uppercase tracking-widest text-outline-variant dark:text-gray-400 font-bold transition-colors">Pilih Ruangan</label>
                    <div class="relative flex items-center">
                        <select name="room_id" class="w-full h-14 px-5 bg-surface-container-low dark:bg-white/5 border-none rounded-xl focus:ring-2 focus:ring-primary/20 dark:focus:ring-emerald-500/50 text-on-surface dark:text-gray-100 dark:placeholder-gray-500 font-medium transition-colors" required>
                            <option value="" class="bg-white dark:bg-[#181c20] text-slate-900 dark:text-gray-100">-- Pilih Ruangan untuk Jadwal ini --</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }} class="bg-white dark:bg-[#181c20] text-slate-900 dark:text-gray-100">{{ $room->nama_ruangan }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-label uppercase tracking-widest text-outline-variant dark:text-gray-400 font-bold transition-colors">Nama Sholat</label>
                    <div class="relative flex items-center">
                        <input name="nama_sholat" value="{{ old('nama_sholat') }}" class="w-full h-14 px-5 bg-surface-container-low dark:bg-white/5 border-none rounded-xl focus:ring-2 focus:ring-primary/20 dark:focus:ring-emerald-500/50 text-on-surface dark:text-gray-100 dark:placeholder-gray-500 font-medium transition-colors" placeholder="Masukkan nama sholat" type="text"/>
                    </div>
                </div>
                
                <div class="space-y-2">
                    <label class="text-xs font-label uppercase tracking-widest text-outline-variant dark:text-gray-400 font-bold transition-colors">Jam Penyemprotan</label>
                    <div class="flex items-center gap-4 bg-surface-container-low dark:bg-white/5 px-5 py-4 rounded-xl transition-colors">
                        <span class="material-symbols-outlined text-primary dark:text-emerald-400">schedule</span>
                        <input name="waktu" value="{{ old('waktu') }}" class="w-full bg-transparent border-none focus:ring-0 text-on-surface dark:text-gray-100 dark:placeholder-gray-500 font-medium" type="time"/>
                    </div>
                    <p class="text-[11px] text-on-surface-variant dark:text-gray-400 pl-1 transition-colors">Sistem akan otomatis mengaktifkan IoT pada waktu ini.</p>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-xs font-label uppercase tracking-widest text-outline-variant dark:text-gray-400 font-bold transition-colors">Mulai Tanggal</label>
                        <div class="relative flex items-center">
                            <input id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" class="w-full h-14 px-5 bg-surface-container-low dark:bg-white/5 border-none rounded-xl focus:ring-2 focus:ring-primary/20 dark:focus:ring-emerald-500/50 text-on-surface dark:text-gray-100 font-medium transition-colors" type="date" onchange="syncCreateTanggalAkhirMin()" required/>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-label uppercase tracking-widest text-outline-variant dark:text-gray-400 font-bold transition-colors">Sampai Tanggal</label>
                        <div class="relative flex items-center">
                            <input id="tanggal_akhir" name="tanggal_akhir" value="{{ old('tanggal_akhir') }}" class="w-full h-14 px-5 bg-surface-container-low dark:bg-white/5 border-none rounded-xl focus:ring-2 focus:ring-primary/20 dark:focus:ring-emerald-500/50 text-on-surface dark:text-gray-100 font-medium transition-colors" type="date" required/>
                        </div>
                    </div>
                </div>
                <p id="create-date-error" class="text-sm text-error dark:text-red-400 mt-2 hidden transition-colors">Tanggal akhir tidak boleh lebih kecil dari tanggal mulai.</p>
                
                <div class="bg-primary-container/10 dark:bg-emerald-500/10 p-5 rounded-xl flex items-start gap-4 transition-colors">
                    <span class="material-symbols-outlined text-primary-container dark:text-emerald-400" style="font-variation-settings: 'FILL' 1;">info</span>
                    <div>
                        <p class="text-xs font-semibold text-on-primary-container dark:text-emerald-200/90 leading-relaxed transition-colors">Jadwal ini akan berulang setiap hari selama rentang tanggal yang ditentukan. Pastikan perangkat IoT dalam keadaan online.</p>
                    </div>
                </div>
            </div>
            
            <div class="px-8 py-8 bg-surface-container-low dark:bg-white/5 border-t border-transparent dark:border-white/5 flex justify-end items-center gap-4 transition-colors">
                <button type="button" onclick="window.location='{{ route('jadwal.index') }}'" class="px-6 py-3 text-sm font-semibold text-outline dark:text-gray-400 hover:text-on-surface dark:hover:text-gray-200 transition-colors">Batal</button>
                <button type="submit" class="bg-primary-gradient text-white px-10 py-3 rounded-full flex items-center gap-2 font-semibold shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-98 transition-all">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0, 'wght' 700;">check</span>
                        Tambah Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="fixed bottom-0 right-0 p-12 opacity-10 pointer-events-none select-none transition-opacity duration-300">
        <span class="material-symbols-outlined text-[300px] text-emerald-900 dark:text-emerald-500/10 transition-colors duration-300">temple_hindu</span>
    </div>

    <script>
        function syncCreateTanggalAkhirMin() {
            const start = document.getElementById('tanggal_mulai');
            const end = document.getElementById('tanggal_akhir');
            if (!start || !end) return;
            if (start.value) {
                end.min = start.value;
                if (end.value && end.value < start.value) {
                    end.value = start.value;
                }
            } else {
                end.min = '';
            }
        }

        function validateCreateDateRange() {
            const start = document.getElementById('tanggal_mulai');
            const end = document.getElementById('tanggal_akhir');
            const error = document.getElementById('create-date-error');
            if (!start || !end || !error) return true;

            const startValue = start.value;
            const endValue = end.value;
            if (!startValue || !endValue) {
                error.classList.add('hidden');
                return true;
            }

            if (endValue < startValue) {
                error.classList.remove('hidden');
                return false;
            }

            error.classList.add('hidden');
            return true;
        }

        document.addEventListener('DOMContentLoaded', function() {
            syncCreateTanggalAkhirMin();
        });
    </script>
@endsection