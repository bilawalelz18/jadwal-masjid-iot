@extends('layouts.app')

@section('content')
    <style>
        .signature-gradient {
            background: linear-gradient(135deg, #006c49 0%, #10b981 100%);
        }
        .glass-overlay {
            background: rgba(247, 249, 251, 0.6);
            backdrop-filter: blur(8px);
        }
        /* Tambahan untuk Dark Mode Overlay */
        .dark .glass-overlay {
            background: rgba(10, 12, 14, 0.7);
        }
        .modal-shadow {
            box-shadow: 0px 20px 40px rgba(25, 28, 30, 0.06);
        }
        /* Tambahan untuk Dark Mode Shadow */
        .dark .modal-shadow {
            box-shadow: 0px 20px 40px rgba(0, 0, 0, 0.5);
        }
        /* Perbaikan Ikon Jam & Tanggal untuk Mode Gelap */
        .dark input[type="time"],
        .dark input[type="date"] {
            color-scheme: dark;
        }
    </style>

    <div class="flex min-h-screen">
        <aside class="h-screen w-64 fixed left-0 top-0 bg-[#f2f4f6] dark:bg-[#111417] border-r border-transparent dark:border-white/5 transition-colors duration-300 flex flex-col py-8 z-10">
            <div class="px-8 mb-10 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full signature-gradient flex items-center justify-center text-white">
                    <span class="material-symbols-outlined" data-icon="mosque">mosque</span>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-[#006c49] dark:text-[#10b981]">Digital Sanctuary</h1>
                    <p class="text-xs text-slate-500 dark:text-gray-400 font-medium">IoT Mosque Management</p>
                </div>
            </div>
            <nav class="flex-1 space-y-2">
                <a class="flex items-center gap-3 py-3 px-6 text-slate-500 dark:text-gray-400 font-medium hover:bg-white/30 dark:hover:bg-white/5 transition-colors duration-200" href="#">
                    <span class="material-symbols-outlined" data-icon="dashboard">dashboard</span>
                    <span class="font-['Manrope'] font-semibold text-sm">Beranda</span>
                </a>
                <a class="flex items-center gap-3 py-3 px-6 text-slate-500 dark:text-gray-400 font-medium hover:bg-white/30 dark:hover:bg-white/5 transition-colors duration-200" href="#">
                    <span class="material-symbols-outlined" data-icon="settings_remote">settings_remote</span>
                    <span class="font-['Manrope'] font-semibold text-sm">Kontrol Manual</span>
                </a>
                <a class="flex items-center gap-3 py-3 px-6 border-l-4 border-[#006c49] dark:border-emerald-400 text-[#006c49] dark:text-emerald-400 bg-white/50 dark:bg-emerald-500/10 font-bold" href="#">
                    <span class="material-symbols-outlined" data-icon="schedule" style="font-variation-settings: 'FILL' 1;">schedule</span>
                    <span class="font-['Manrope'] font-semibold text-sm">Jadwal Sholat</span>
                </a>
                <a class="flex items-center gap-3 py-3 px-6 {{ request()->routeIs('devices.*') ? 'border-l-4 border-[#006c49] dark:border-emerald-400 text-[#006c49] dark:text-emerald-400 bg-white/50 dark:bg-emerald-500/10 font-bold' : 'text-slate-500 dark:text-gray-400 font-medium hover:bg-white/30 dark:hover:bg-white/5' }} transition-colors duration-200" href="{{ route('devices.index') }}">
                    <span class="material-symbols-outlined" data-icon="router">router</span>
                    <span class="font-['Manrope'] font-semibold text-sm">Data Perangkat</span>
                </a>
                <a class="flex items-center gap-3 py-3 px-6 text-slate-500 dark:text-gray-400 font-medium hover:bg-white/30 dark:hover:bg-white/5 transition-colors duration-200" href="#">
                    <span class="material-symbols-outlined" data-icon="notifications">notifications</span>
                    <span class="font-['Manrope'] font-semibold text-sm">Notifikasi</span>
                </a>
                <a class="flex items-center gap-3 py-3 px-6 text-slate-500 dark:text-gray-400 font-medium hover:bg-white/30 dark:hover:bg-white/5 transition-colors duration-200" href="#">
                    <span class="material-symbols-outlined" data-icon="settings">settings</span>
                    <span class="font-['Manrope'] font-semibold text-sm">Pengaturan</span>
                </a>
            </nav>
            <div class="px-6 mt-auto">
                <a class="flex items-center gap-3 py-3 px-4 text-slate-500 dark:text-gray-500 hover:text-error dark:hover:text-red-400 transition-colors" href="#">
                    <span class="material-symbols-outlined" data-icon="logout">logout</span>
                    <span class="font-['Manrope'] font-semibold text-sm">Logout</span>
                </a>
            </div>
        </aside>

        <main class="ml-64 w-full bg-surface dark:bg-[#0a0c0e] transition-colors duration-300">
            <header class="fixed top-0 right-0 w-[calc(100%-16rem)] h-16 bg-[#f7f9fb]/80 dark:bg-[#0a0c0e]/80 backdrop-blur-lg border-b border-transparent dark:border-white/5 transition-colors duration-300 flex justify-between items-center px-8 w-full z-20">
                <h2 class="text-lg font-bold text-slate-900 dark:text-gray-100 font-['Manrope']">Jadwal Sholat</h2>
                <div class="flex items-center gap-4">
                    <div class="bg-surface-container-low dark:bg-white/5 px-4 py-2 rounded-full flex items-center gap-2">
                        <span class="material-symbols-outlined text-slate-400 dark:text-gray-500" data-icon="search">search</span>
                        <input class="bg-transparent border-none focus:ring-0 text-sm w-48" placeholder="Cari jadwal..." type="text"/>
                    </div>
                    <button class="text-slate-600 dark:text-gray-400 hover:opacity-80 transition-opacity">
                        <span class="material-symbols-outlined" data-icon="account_circle">account_circle</span>
                    </button>
                </div>
            </header>

            <div class="pt-24 px-8 pb-12">
                <div class="mb-8">
                    <h3 class="text-3xl font-headline font-bold text-on-surface dark:text-gray-100">Manajemen Penyemprotan</h3>
                    <p class="text-on-surface-variant dark:text-gray-400 mt-2">Daftar jadwal otomatisasi pewangi berdasarkan waktu sholat.</p>
                </div>
                <div class="bg-surface-container-lowest dark:bg-[#111417] border border-transparent dark:border-white/5 rounded-xl overflow-hidden transition-colors duration-300">
                    <table class="w-full text-left">
                        <thead class="bg-surface-container-low dark:bg-white/5 text-on-surface-variant dark:text-gray-400 transition-colors duration-300">
                            <tr>
                                <th class="px-8 py-4 font-headline text-sm font-semibold uppercase tracking-wider">Waktu Sholat</th>
                                <th class="px-8 py-4 font-headline text-sm font-semibold uppercase tracking-wider">Jam Sholat</th>
                                <th class="px-8 py-4 font-headline text-sm font-semibold uppercase tracking-wider">Penyemprotan</th>
                                <th class="px-8 py-4 font-headline text-sm font-semibold uppercase tracking-wider">Status</th>
                                <th class="px-8 py-4 font-headline text-sm font-semibold uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-0">
                            <tr class="bg-surface-container-lowest dark:bg-[#111417] hover:bg-surface-container-low dark:hover:bg-white/5 transition-colors h-16">
                                <td class="px-8 py-4 font-semibold text-on-surface dark:text-gray-200">Subuh</td>
                                <td class="px-8 py-4 text-slate-500 dark:text-gray-400">04:32</td>
                                <td class="px-8 py-4 text-primary dark:text-emerald-400 font-bold">04:35</td>
                                <td class="px-8 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-primary-container/20 dark:bg-emerald-500/10 text-primary dark:text-emerald-400">Aktif</span>
                                </td>
                                <td class="px-8 py-4">
                                    <button class="text-[#006c49] dark:text-emerald-400 font-bold text-sm hover:underline">Edit</button>
                                </td>
                            </tr>
                            <tr class="bg-surface-container-low dark:bg-white/5 hover:bg-surface-container-high dark:hover:bg-white/10 transition-colors h-16">
                                <td class="px-8 py-4 font-semibold text-on-surface dark:text-gray-200">Dzuhur</td>
                                <td class="px-8 py-4 text-slate-500 dark:text-gray-400">11:58</td>
                                <td class="px-8 py-4 text-primary dark:text-emerald-400 font-bold">12:05</td>
                                <td class="px-8 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-primary-container/20 dark:bg-emerald-500/10 text-primary dark:text-emerald-400">Aktif</span>
                                </td>
                                <td class="px-8 py-4">
                                    <button class="text-[#006c49] dark:text-emerald-400 font-bold text-sm hover:underline">Edit</button>
                                </td>
                            </tr>
                            <tr class="bg-surface-container-lowest dark:bg-[#111417] hover:bg-surface-container-low dark:hover:bg-white/5 transition-colors h-16">
                                <td class="px-8 py-4 font-semibold text-on-surface dark:text-gray-200">Ashar</td>
                                <td class="px-8 py-4 text-slate-500 dark:text-gray-400">15:15</td>
                                <td class="px-8 py-4 text-primary dark:text-emerald-400 font-bold">15:20</td>
                                <td class="px-8 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-primary-container/20 dark:bg-emerald-500/10 text-primary dark:text-emerald-400">Aktif</span>
                                </td>
                                <td class="px-8 py-4">
                                    <button class="text-[#006c49] dark:text-emerald-400 font-bold text-sm hover:underline">Edit</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 glass-overlay">
        <div class="bg-surface-container-lowest dark:bg-[#111417] w-full max-w-lg rounded-xl overflow-hidden modal-shadow p-10 animate-scale-up border-[0.5px] border-outline-variant/15 dark:border-white/10">
            <div class="mb-8">
                <h2 class="text-2xl font-headline font-bold text-on-surface dark:text-gray-100">Edit Jadwal Sholat</h2>
                <p class="text-on-surface-variant dark:text-gray-400 text-sm mt-2">Sesuaikan waktu penyemprotan otomatis untuk waktu sholat ini.</p>
            </div>
            <form action="{{ route('jadwal.update', $jadwal->id) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')
                
                <div class="space-y-3">
                    <label class="block text-xs font-bold uppercase tracking-widest text-outline dark:text-gray-400">Pilih Ruangan</label>
                    <select name="room_id" class="w-full bg-surface-container-low dark:bg-white/5 px-5 py-4 rounded-lg border-none focus:ring-2 focus:ring-primary/20 dark:focus:ring-emerald-500/50 text-on-surface dark:text-gray-100 dark:placeholder-gray-500 font-semibold" required>
                        <option value="" class="bg-white dark:bg-[#181c20] text-slate-900 dark:text-gray-100">-- Pilih Ruangan --</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}" {{ (old('room_id', $jadwal->room_id) == $room->id) ? 'selected' : '' }} class="bg-white dark:bg-[#181c20] text-slate-900 dark:text-gray-100">{{ $room->nama_ruangan }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="space-y-3">
                    <label class="block text-xs font-bold uppercase tracking-widest text-outline dark:text-gray-400">Nama Sholat</label>
                    <input name="nama_sholat" value="{{ old('nama_sholat', $jadwal->nama_sholat) }}" class="w-full bg-surface-container-low dark:bg-white/5 px-5 py-4 rounded-lg border-none focus:ring-2 focus:ring-primary/20 dark:focus:ring-emerald-500/50 text-on-surface dark:text-gray-100 dark:placeholder-gray-500 font-semibold" placeholder="Masukkan nama sholat" type="text" required/>
                </div>
                <div class="space-y-3">
                    <label class="block text-xs font-bold uppercase tracking-widest text-outline dark:text-gray-400">Jam Penyemprotan</label>
                    <div class="flex items-center gap-4 bg-surface-container-low dark:bg-white/5 px-5 py-4 rounded-lg">
                        <span class="material-symbols-outlined text-primary dark:text-emerald-400" data-icon="timer">timer</span>
                        <input name="waktu" value="{{ old('waktu', $jadwal->waktu) }}" class="bg-transparent border-none focus:ring-0 w-full text-lg font-bold text-on-surface dark:text-gray-100 dark:placeholder-gray-500" type="time" required/>
                    </div>
                </div>
                <div class="space-y-3">
                    <label class="block text-xs font-bold uppercase tracking-widest text-outline dark:text-gray-400">Tanggal</label>
                    <div class="flex items-center gap-3 bg-surface-container-low dark:bg-white/5 px-5 py-4 rounded-lg">
                        <span class="material-symbols-outlined text-primary text-sm" data-icon="calendar_today">calendar_today</span>
                        <input id="edit-tanggal" name="tanggal" class="bg-transparent border-none p-0 focus:ring-0 w-full text-base font-bold text-on-surface dark:text-gray-100 dark:placeholder-gray-500" type="date" value="{{ old('tanggal', $jadwal->tanggal) }}" required/>
                    </div>
                </div>
                <div class="flex gap-4 pt-4">
                    <button type="button" onclick="window.location='{{ route('jadwal.index') }}'" class="flex-1 py-4 px-8 rounded-full font-headline font-bold text-secondary dark:text-gray-400 hover:bg-surface-container-high dark:hover:bg-white/10 transition-all text-sm">Batal</button>
                    <button class="flex-[1.5] signature-gradient py-4 px-8 rounded-full font-headline font-bold text-white shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all text-sm flex items-center justify-center gap-2" type="submit">
                        <span class="material-symbols-outlined text-lg" data-icon="check">check</span>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="fixed bottom-0 left-0 right-0 h-32 pointer-events-none bg-gradient-to-t from-white/20 dark:from-[#0a0c0e]/80 to-transparent z-0"></div>
@endsection