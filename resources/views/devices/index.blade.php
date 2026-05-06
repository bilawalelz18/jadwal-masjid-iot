@extends('layouts.app')

@section('content')
    <div class="pt-24 px-10 pb-12">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-on-surface dark:text-gray-100 mb-2 font-headline transition-colors duration-300">Manajemen Perangkat IoT</h1>
                <p class="text-secondary dark:text-gray-400 font-body transition-colors duration-300">Kelola dan pantau alat penyemprot otomatis (ESP32) di setiap ruangan.</p>
            </div>
            <button onclick="showAddDeviceModal()" class="bg-primary dark:bg-emerald-600 hover:bg-opacity-90 dark:hover:bg-emerald-500 text-white px-8 py-3 rounded-full font-bold shadow-lg shadow-emerald-900/10 transition-all flex items-center gap-2 w-fit">
                <span class="material-symbols-outlined text-xl">add</span>
                <span>Tambah Perangkat</span>
            </button>
        </div>

        <div class="bg-surface-container-lowest dark:bg-[#111417] rounded-xl shadow-[0px_20px_40px_rgba(25,28,30,0.06)] border border-transparent dark:border-white/5 transition-colors duration-300 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-high/30 dark:bg-white/5 transition-colors duration-300">
                            <th class="px-8 py-6 text-[11px] font-bold text-secondary dark:text-gray-400 tracking-widest uppercase font-label">Device ID (MAC)</th>
                            <th class="px-8 py-6 text-[11px] font-bold text-secondary dark:text-gray-400 tracking-widest uppercase font-label">Nama Perangkat</th>
                            <th class="px-8 py-6 text-[11px] font-bold text-secondary dark:text-gray-400 tracking-widest uppercase font-label">Lokasi / Ruangan</th>
                            <th class="px-8 py-6 text-[11px] font-bold text-secondary dark:text-gray-400 tracking-widest uppercase font-label">API Key</th>
                            <th class="px-8 py-6 text-[11px] font-bold text-secondary dark:text-gray-400 tracking-widest uppercase font-label">Status</th>
                            <th class="px-8 py-6 text-[11px] font-bold text-secondary dark:text-gray-400 tracking-widest uppercase font-label text-center w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-transparent">
                        @forelse($devices as $device)
                            <tr class="hover:bg-surface-container-low/50 dark:hover:bg-white/5 transition-colors duration-300">
                                <td class="px-8 py-6 text-sm font-semibold text-on-surface dark:text-gray-200">{{ $device->device_id }}</td>
                                <td class="px-8 py-6 text-sm font-bold text-on-surface dark:text-gray-200">
                                    <div class="flex items-center gap-3">
                                        <span class="material-symbols-outlined text-primary dark:text-emerald-400">router</span>
                                        {{ $device->nama_perangkat }}
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-sm font-semibold text-secondary dark:text-gray-300">{{ $device->room ? $device->room->nama_ruangan : 'Tidak ada ruangan' }}</td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-2">
                                        <input type="password" value="{{ $device->api_key }}" class="bg-transparent border-none focus:ring-0 text-sm font-semibold text-slate-500 w-24 pointer-events-none" readonly id="api-key-{{ $device->id }}">
                                        <button type="button" onclick="copyToClipboard('{{ $device->api_key }}')" class="text-primary hover:text-emerald-700 dark:text-emerald-400 text-xs font-bold" title="Salin API Key">Salin</button>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    @if($device->status_koneksi === 'online')
                                        <span class="flex items-center gap-2 text-xs font-bold text-primary dark:text-emerald-400">
                                            <span class="h-2 w-2 rounded-full bg-primary animate-pulse"></span> Online
                                        </span>
                                    @else
                                        <span class="flex items-center gap-2 text-xs font-bold text-error dark:text-red-400">
                                            <span class="h-2 w-2 rounded-full bg-error"></span> Offline
                                        </span>
                                    @endif
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <div class="flex items-center justify-center gap-3">
                                        <form id="form-reset-{{ $device->id }}" action="{{ route('devices.reset', $device->id) }}" method="POST">
                                            @csrf
                                            <button type="button" onclick="showConfirmResetModal({{ $device->id }})" class="p-2 text-secondary dark:text-gray-500 hover:text-orange-500 transition-colors" title="Reset API Key">
                                                <span class="material-symbols-outlined text-xl">restart_alt</span>
                                            </button>
                                        </form>
                                        <form id="form-delete-{{ $device->id }}" action="{{ route('devices.destroy', $device->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="button" onclick="showConfirmDeleteModal({{ $device->id }})" class="p-2 text-secondary dark:text-gray-500 hover:text-error dark:hover:text-red-400 transition-colors" title="Hapus Perangkat">
                                                <span class="material-symbols-outlined text-xl">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-10 text-center text-slate-500 dark:text-gray-500 font-medium">
                                    Belum ada perangkat IoT yang terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="addDeviceModal" class="hidden fixed inset-0 bg-black/40 dark:bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center">
        <div class="bg-surface-container-lowest dark:bg-[#111417] border border-transparent dark:border-white/5 rounded-3xl shadow-2xl max-w-lg w-full mx-4 overflow-hidden">
            <div class="px-8 py-6 border-b border-outline/10 dark:border-white/5 flex justify-between items-center">
                <h2 class="text-xl font-bold font-headline text-on-surface dark:text-gray-100">Tambah Perangkat IoT</h2>
                <button onclick="closeAddDeviceModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-gray-300"><span class="material-symbols-outlined">close</span></button>
            </div>
            
            <form action="{{ route('devices.store') }}" method="POST" class="p-8 space-y-5">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-outline dark:text-gray-400 mb-2">Nama Perangkat</label>
                    <input type="text" name="nama_perangkat" class="w-full bg-surface-container-low dark:bg-white/5 px-5 py-3 rounded-lg border-none focus:ring-2 focus:ring-primary/20 text-sm font-semibold text-on-surface dark:text-gray-100" placeholder="Misal: Pewangi Utama Depan" required>
                </div>
                
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-outline dark:text-gray-400 mb-2">Lokasi Ruangan</label>
                    <select name="room_id" class="w-full bg-surface-container-low dark:bg-white/5 px-5 py-3 rounded-lg border-none focus:ring-2 focus:ring-primary/20 text-sm font-semibold text-on-surface dark:text-gray-100" required>
                        <option value="" class="bg-white dark:bg-[#181c20]">-- Pilih Lokasi --</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}" class="bg-white dark:bg-[#181c20]">{{ $room->nama_ruangan }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-outline dark:text-gray-400 mb-2">Device ID (MAC Address)</label>
                    <input type="text" name="device_id" class="w-full bg-surface-container-low dark:bg-white/5 px-5 py-3 rounded-lg border-none focus:ring-2 focus:ring-primary/20 text-sm font-semibold text-on-surface dark:text-gray-100 uppercase" placeholder="Misal: 24:6F:28:XX:XX:XX" required>
                    <p class="text-[10px] text-slate-500 mt-1">Gunakan Mac Address ESP32 agar unik dan tidak tertukar.</p>
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="button" onclick="closeAddDeviceModal()" class="flex-1 px-4 py-3 rounded-full border border-outline/30 text-on-surface dark:text-gray-300 font-bold hover:bg-surface-container transition-colors">Batal</button>
                    <button type="submit" class="flex-1 px-4 py-3 rounded-full bg-primary dark:bg-emerald-600 text-white font-bold hover:opacity-90 transition-opacity">Simpan Perangkat</button>
                </div>
            </form>
        </div>
    </div>

    <div id="confirmResetModal" class="hidden fixed inset-0 bg-black/40 dark:bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center">
        <div class="bg-[#181c20] rounded-3xl shadow-2xl max-w-sm w-full mx-4 p-8 text-center border border-white/5">
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 bg-[#0f211b] rounded-full flex items-center justify-center">
                    <span class="material-symbols-outlined text-3xl text-emerald-400">restart_alt</span>
                </div>
            </div>
            <h2 class="text-2xl font-bold font-headline text-gray-100 mb-3">Konfirmasi Reset API</h2>
            <p class="text-gray-400 text-sm mb-8 leading-relaxed">Apakah Anda yakin ingin mereset API Key? Alat IoT harus di-flash ulang dengan Key baru.</p>
            <div class="flex gap-4">
                <button type="button" onclick="closeConfirmResetModal()" class="flex-1 py-4 rounded-full bg-[#202428] hover:bg-[#2a2f34] text-gray-200 font-bold transition-colors">
                    Batal
                </button>
                <button type="button" onclick="executeReset()" class="flex-1 py-4 rounded-full bg-emerald-500 hover:bg-emerald-600 text-[#0a0c0e] font-bold flex items-center justify-center gap-2 transition-colors">
                    Reset API
                </button>
            </div>
        </div>
    </div>

    <div id="confirmDeleteModal" class="hidden fixed inset-0 bg-black/40 dark:bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center">
        <div class="bg-[#181c20] rounded-3xl shadow-2xl max-w-sm w-full mx-4 p-8 text-center border border-white/5">
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 bg-[#2a1315] rounded-full flex items-center justify-center">
                    <span class="material-symbols-outlined text-3xl text-red-400">delete</span>
                </div>
            </div>
            <h2 class="text-2xl font-bold font-headline text-gray-100 mb-3">Hapus Perangkat</h2>
            <p class="text-gray-400 text-sm mb-8 leading-relaxed">Apakah Anda yakin ingin menghapus perangkat IoT ini dari sistem?</p>
            <div class="flex gap-4">
                <button type="button" onclick="closeConfirmDeleteModal()" class="flex-1 py-4 rounded-full bg-[#202428] hover:bg-[#2a2f34] text-gray-200 font-bold transition-colors">
                    Batal
                </button>
                <button type="button" onclick="executeDelete()" class="flex-1 py-4 rounded-full bg-red-500 hover:bg-red-600 text-white font-bold flex items-center justify-center gap-2 transition-colors">
                    Hapus
                </button>
            </div>
        </div>
    </div>

    <script>
        let currentResetId = null;
        let currentDeleteId = null;

        function showConfirmResetModal(id) {
            currentResetId = id;
            document.getElementById('confirmResetModal').classList.remove('hidden');
        }
        function closeConfirmResetModal() {
            document.getElementById('confirmResetModal').classList.add('hidden');
            currentResetId = null;
        }
        function executeReset() {
            if(currentResetId) {
                document.getElementById('form-reset-' + currentResetId).submit();
            }
        }

        function showConfirmDeleteModal(id) {
            currentDeleteId = id;
            document.getElementById('confirmDeleteModal').classList.remove('hidden');
        }
        function closeConfirmDeleteModal() {
            document.getElementById('confirmDeleteModal').classList.add('hidden');
            currentDeleteId = null;
        }
        function executeDelete() {
            if(currentDeleteId) {
                document.getElementById('form-delete-' + currentDeleteId).submit();
            }
        }

        function showAddDeviceModal() {
            document.getElementById('addDeviceModal').classList.remove('hidden');
        }
        function closeAddDeviceModal() {
            document.getElementById('addDeviceModal').classList.add('hidden');
        }
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('API Key berhasil disalin: ' + text);
            });
        }
    </script>
@endsection