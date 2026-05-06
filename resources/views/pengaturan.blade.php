@extends('layouts.app')

@section('content')
<style>
    .headline { font-family: 'Manrope', sans-serif; }
    .font-label { font-family: 'Inter', sans-serif; }
    .signature-gradient {
        background: linear-gradient(135deg, #006c49 0%, #10b981 100%);
    }
</style>

@if(session('success'))
<div class="max-w-5xl mx-auto mt-4 px-12 hidden">
    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl flex items-center gap-3 shadow-sm">
        <span class="material-symbols-outlined">check_circle</span>
        <p class="font-bold text-sm">{{ session('success') }}</p>
    </div>
</div>
@endif

<div class="px-12 py-8 max-w-5xl">
    <div class="grid grid-cols-1 gap-12">
        
        <section class="space-y-6">
            <div class="flex items-end justify-between">
                <div>
                    <h3 class="text-xl font-semibold text-on-surface dark:text-gray-100 font-headline">Pengaturan Akun</h3>
                    <p class="text-sm text-on-surface-variant/70 dark:text-gray-400">Kelola informasi profil dan keamanan akses Anda.</p>
                </div>
            </div>
            
            <div class="bg-surface-container-lowest dark:bg-[#111417] border border-transparent dark:border-white/5 rounded-xl transition-colors duration-300 p-8 shadow-sm">
                <div class="flex flex-col gap-8">
                    <div class="flex items-center gap-6 pb-6">
                        <div class="relative group">
                            <div class="w-24 h-24 rounded-full bg-surface-container-low flex items-center justify-center overflow-hidden border-4 border-surface dark:border-[#0a0c0e]">
                                <img alt="User Profile Avatar" class="w-full h-full object-cover" 
                                     src="{{ auth()->user()?->photo ? asset('storage/' . auth()->user()->photo) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()?->name ?? 'Admin') . '&color=006c49&background=e0f2fe' }}"/>
                            </div>
                            <a href="{{ route('pengaturan.edit') }}" class="absolute bottom-0 right-0 p-2 signature-gradient rounded-full text-white shadow-lg hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-sm">edit</span>
                            </a>
                        </div>
                        <div>
                            <p class="font-bold text-lg dark:text-gray-100">{{ auth()->user()?->name ?? 'Administrator Masjid' }}</p>
                            <p class="text-sm text-on-surface-variant dark:text-gray-400 font-medium">Pengurus Masjid </p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
                        <div class="space-y-2">
                            <label class="font-label text-[10px] uppercase tracking-wider text-on-surface-variant/80 dark:text-gray-400">Nama Lengkap</label>
                            <input class="w-full bg-surface-container-low dark:bg-white/5 border-none dark:text-gray-100 rounded-lg p-4 font-body text-sm focus:ring-2 focus:ring-primary/20" type="text" value="{{ auth()->user()?->name ?? 'Abdullah Al-Fatih' }}" readonly/>
                        </div>
                        <div class="space-y-2">
                            <label class="font-label text-[10px] uppercase tracking-wider text-on-surface-variant/80 dark:text-gray-400">Email</label>
                            <input class="w-full bg-surface-container-low dark:bg-white/5 border-none dark:text-gray-100 rounded-lg p-4 font-body text-sm focus:ring-2 focus:ring-primary/20" type="email" value="{{ auth()->user()?->email ?? 'admin@as-salam.or.id' }}" readonly/>
                        </div>
                        
                        <div class="md:col-span-2 pt-4">
                            <div class="p-6 bg-surface-container-low dark:bg-white/5 rounded-xl transition-colors duration-300 flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-surface-container-highest dark:bg-white/10 flex items-center transition-colors duration-300 justify-center">
                                        <span class="material-symbols-outlined text-primary dark:text-emerald-400">key</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-sm">Ganti Kata Sandi</p>
                                        <p class="text-xs text-on-surface-variant dark:text-gray-400">Lindungi akun administrator utama Anda</p>
                                    </div>
                                </div>
                                <a href="{{ route('pengaturan.edit') }}" class="px-6 py-2 border-2 text-on-surface dark:text-gray-300 font-semibold text-sm rounded-full hover:bg-white dark:hover:bg-white/10 border-outline-variant/30 dark:border-white/10 transition-colors">
                                    Perbarui Sandi
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="space-y-6">
            <div>
                <h3 class="text-xl font-semibold text-on-surface dark:text-gray-100 font-headline">Personalisasi</h3>
                <p class="text-sm text-on-surface-variant/70 dark:text-gray-400">Sesuaikan tampilan antarmuka sesuai keinginan Anda.</p>
            </div>
            
            <div class="bg-surface-container-lowest dark:bg-[#111417] border border-transparent dark:border-white/5 rounded-xl transition-colors duration-300 p-8 shadow-sm transition-all">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-6">
                        <div class="w-14 h-14 rounded-2xl bg-primary/10 dark:bg-emerald-500/10 flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary dark:text-emerald-400 text-3xl">dark_mode</span>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-on-surface dark:text-gray-100">Mode Malam</h4>
                            <p class="text-sm text-on-surface-variant/80 dark:text-gray-400 max-w-md">Gunakan mode malam untuk kenyamanan visual Anda.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <div class="flex items-center bg-surface-container-low dark:bg-white/5 p-1.5 rounded-full border border-outline-variant/20 dark:border-white/10 transition-colors duration-300">
                            <button id="sanctuary-light" type="button" onclick="setSanctuaryMode('light')" class="flex items-center justify-center w-12 h-10 rounded-full bg-white dark:bg-emerald-500 shadow-sm text-primary dark:text-[#0a0c0e] transition-all">
                                <span class="material-symbols-outlined">light_mode</span>
                            </button>
                            <button id="sanctuary-dark" type="button" onclick="setSanctuaryMode('dark')" class="flex items-center justify-center w-12 h-10 rounded-full text-on-surface-variant/50 dark:text-gray-500 hover:text-on-surface dark:hover:text-gray-300 transition-all">
                                <span class="material-symbols-outlined">dark_mode</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
</div>

<script>
    function setSanctuaryMode(mode) {
        const html = document.documentElement;
        const lightButton = document.getElementById('sanctuary-light');
        const darkButton = document.getElementById('sanctuary-dark');

        if (mode === 'dark') {
            html.classList.add('dark');
            localStorage.setItem('sanctuary_mode', 'dark');
            darkButton.classList.add('bg-white', 'text-primary', 'dark:bg-emerald-500', 'dark:text-[#0a0c0e]');
            darkButton.classList.remove('text-on-surface-variant', 'hover:text-on-surface', 'dark:text-gray-500', 'dark:hover:text-gray-300');
            lightButton.classList.remove('bg-white', 'text-primary', 'dark:bg-emerald-500', 'dark:text-[#0a0c0e]');
            lightButton.classList.add('text-on-surface-variant', 'hover:text-on-surface', 'dark:text-gray-500', 'dark:hover:text-gray-300');
        } else {
            html.classList.remove('dark');
            localStorage.setItem('sanctuary_mode', 'light');
            lightButton.classList.add('bg-white', 'text-primary', 'dark:bg-emerald-500', 'dark:text-[#0a0c0e]');
            lightButton.classList.remove('text-on-surface-variant', 'hover:text-on-surface', 'dark:text-gray-500', 'dark:hover:text-gray-300');
            darkButton.classList.remove('bg-white', 'text-primary', 'dark:bg-emerald-500', 'dark:text-[#0a0c0e]');
            darkButton.classList.add('text-on-surface-variant', 'hover:text-on-surface', 'dark:text-gray-500', 'dark:hover:text-gray-300');
        }
    }

    function initSanctuaryMode() {
        const mode = localStorage.getItem('sanctuary_mode') || 'light';
        setSanctuaryMode(mode);
    }

    document.addEventListener('DOMContentLoaded', initSanctuaryMode);
</script>
@endsection