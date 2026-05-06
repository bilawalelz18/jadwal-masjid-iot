<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>IoT Dashboard</title>
    <script>
        (function() {
            const mode = localStorage.getItem('sanctuary_mode') || 'light';
            if (mode === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Inter:wght@300;400;500;600;700&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
    tailwind.config = {
    darkMode: "class",
    theme: {
        extend: {
        "colors": {
            "on-surface": "#191c1e",
            "surface-container-highest": "#e0e3e5",
            "primary-fixed": "#6ffbbe",
            "on-tertiary-fixed": "#410005",
            "tertiary-fixed": "#ffdad7",
            "inverse-surface": "#2d3133",
            "tertiary-container": "#fc7c78",
            "on-tertiary-container": "#711419",
            "error-container": "#ffdad6",
            "outline": "#6c7a71",
            "on-error-container": "#93000a",
            "on-secondary-fixed-variant": "#3a485c",
            "surface-variant": "#e0e3e5",
            "on-error": "#ffffff",
            "surface-bright": "#f7f9fb",
            "on-primary-fixed": "#002113",
            "on-secondary-fixed": "#0d1c2f",
            "surface-container-lowest": "#ffffff",
            "on-secondary-container": "#57657b",
            "secondary-fixed": "#d5e3fd",
            "on-background": "#191c1e",
            "inverse-on-surface": "#eff1f3",
            "primary-container": "#10b981",
            "primary-fixed-dim": "#4edea3",
            "outline-variant": "#bbcabf",
            "surface-container-high": "#e6e8ea",
            "tertiary": "#a43a3a",
            "secondary": "#515f74",
            "on-primary-container": "#00422b",
            "background": "#f7f9fb",
            "surface-container": "#eceef0",
            "inverse-primary": "#4edea3",
            "surface-container-low": "#f2f4f6",
            "on-primary": "#ffffff",
            "secondary-fixed-dim": "#b9c7e0",
            "secondary-container": "#d5e3fd",
            "surface-dim": "#d8dadc",
            "surface-tint": "#006c49",
            "on-tertiary": "#ffffff",
            "tertiary-fixed-dim": "#ffb3af",
            "on-secondary": "#ffffff",
            "surface": "#f7f9fb",
            "on-tertiary-fixed-variant": "#842225",
            "on-surface-variant": "#3c4a42",
            "primary": "#006c49",
            "on-primary-fixed-variant": "#005236",
            "error": "#ba1a1a"
        },
        "borderRadius": {
            "DEFAULT": "1rem",
            "lg": "2rem",
            "xl": "3rem",
            "full": "9999px"
        },
        "fontFamily": {
            "headline": ["Manrope"],
            "body": ["Inter"],
            "label": ["Inter"]
        }
        },
    },
    }
</script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, .font-headline { font-family: 'Manrope', sans-serif; }
        .glass-card {
            background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.5); box-shadow: 0 8px 32px 0 rgba(0, 108, 73, 0.04);
        }
        .premium-glow { box-shadow: 0 0 20px rgba(16, 185, 129, 0.3), 0 10px 15px -3px rgba(16, 185, 129, 0.2); }
        .bg-premium-gradient { background: linear-gradient(135deg, #006c49 0%, #10b981 100%); }
        @keyframes soft-pulse { 0%, 100% { opacity: 0.4; transform: scale(0.98); } 50% { opacity: 1; transform: scale(1.02); } }
        .animate-soft-pulse { animation: soft-pulse 3s ease-in-out infinite; }
    </style>
</head>
<body class="bg-surface dark:bg-[#0a0c0e] text-on-surface dark:text-gray-100 transition-colors duration-300">

    <aside class="bg-slate-50 dark:bg-[#111417] h-screen w-64 fixed left-0 top-0 overflow-y-auto z-50 border-r border-slate-100 dark:border-white/5 flex flex-col py-8 transition-colors duration-300">
        <div class="px-6 mb-10 flex items-center gap-3">
            <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-500/20">
                <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400" style="font-variation-settings: 'FILL' 1;">mosque</span>
            </div>
            <div>
                <h1 class="text-l font-bold text-emerald-800 dark:text-emerald-400 font-headline tracking-tight leading-none">Dashboard Admin</h1>
                <p class="text-[10px] uppercase font-bold text-slate-500 dark:text-gray-500 font-headline mt-1 tracking-widest">Pengurus Masjid</p>
            </div>
        </div>

        <nav class="flex-1 space-y-1">
                <a class="flex items-center gap-3 px-6 py-4 {{ request()->routeIs('dashboard') ? 'border-l-4 border-emerald-600 dark:border-emerald-400 bg-emerald-50/50 dark:bg-emerald-500/10 text-emerald-900 dark:text-emerald-300 font-bold' : 'text-slate-600 dark:text-gray-400 hover:bg-slate-100 dark:hover:bg-white/5 font-semibold' }} transition-colors duration-200" href="{{ route('dashboard') }}">
                    <span class="material-symbols-outlined" data-icon="dashboard">dashboard</span>
                    <span class="font-['Manrope'] tracking-tight">Beranda</span>
                </a>
                
                <a class="flex items-center gap-3 px-6 py-4 {{ request()->routeIs('kontrol.manual') ? 'border-l-4 border-emerald-600 dark:border-emerald-400 bg-emerald-50/50 dark:bg-emerald-500/10 text-emerald-900 dark:text-emerald-300 font-bold' : 'text-slate-600 dark:text-gray-400 hover:bg-slate-100 dark:hover:bg-white/5 font-semibold' }} transition-colors duration-200" href="{{ route('kontrol.manual') }}">
                    <span class="material-symbols-outlined" data-icon="tune">tune</span>
                    <span class="font-['Manrope'] tracking-tight">Kontrol Manual</span>
                </a>
                
                <a class="flex items-center gap-3 px-6 py-4 {{ request()->routeIs('jadwal.*') ? 'border-l-4 border-emerald-600 dark:border-emerald-400 bg-emerald-50/50 dark:bg-emerald-500/10 text-emerald-900 dark:text-emerald-300 font-bold' : 'text-slate-600 dark:text-gray-400 hover:bg-slate-100 dark:hover:bg-white/5 font-semibold' }} transition-colors duration-200" href="{{ route('jadwal.index') }}">
                    <span class="material-symbols-outlined" data-icon="schedule">schedule</span>
                    <span class="font-['Manrope'] tracking-tight">Jadwal Sholat</span>
                </a>


                <a class="flex items-center gap-3 px-6 py-4 {{ request()->routeIs('devices.*') ? 'border-l-4 border-emerald-600 dark:border-emerald-400 bg-emerald-50/50 dark:bg-emerald-500/10 text-emerald-900 dark:text-emerald-300 font-bold' : 'text-slate-600 dark:text-gray-400 hover:bg-slate-100 dark:hover:bg-white/5 font-semibold' }} transition-colors duration-200" href="{{ route('devices.index') }}">
                    <span class="material-symbols-outlined" data-icon="router">router</span>
                    <span class="font-['Manrope'] tracking-tight">Data Perangkat</span>
                </a>
                <a class="flex items-center gap-3 px-6 py-4 {{ request()->routeIs('notifikasi.index') ? 'border-l-4 border-emerald-600 dark:border-emerald-400 bg-emerald-50/50 dark:bg-emerald-500/10 text-emerald-900 dark:text-emerald-300 font-bold' : 'text-slate-600 dark:text-gray-400 hover:bg-slate-100 dark:hover:bg-white/5 font-semibold' }} transition-colors duration-200" href="{{ route('notifikasi.index') }}">
                    <span class="material-symbols-outlined" data-icon="notifications">notifications</span>
                    <span class="font-['Manrope'] tracking-tight">Notifikasi</span>
                </a>

                <a class="flex items-center gap-3 px-6 py-4 {{ request()->routeIs('pengaturan.index') || request()->routeIs('pengaturan.edit') ? 'border-l-4 border-emerald-600 dark:border-emerald-400 bg-emerald-50/50 dark:bg-emerald-500/10 text-emerald-900 dark:text-emerald-300 font-bold' : 'text-slate-600 dark:text-gray-400 hover:bg-slate-100 dark:hover:bg-white/5 font-semibold' }} transition-colors duration-200" href="{{ route('pengaturan.index') }}">
                    <span class="material-symbols-outlined" data-icon="settings">settings</span>
                    <span class="font-['Manrope'] tracking-tight">Pengaturan</span>
                </a>
                
        </nav>

        <div class="px-6 mt-auto flex flex-col gap-4">
            <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-white dark:bg-[#181c20] border border-slate-200 dark:border-white/10 shadow-sm transition-colors duration-300">
                <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-emerald-100 dark:border-emerald-500/30 shrink-0">
                    @if(auth()->check() && auth()->user()->photo)
                        <img src="{{ asset('storage/' . auth()->user()->photo) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center">
                            <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-lg">person</span>
                        </div>
                    @endif
                </div>
                <div class="flex flex-col overflow-hidden">
                    <span class="text-sm font-bold text-slate-800 dark:text-gray-100 truncate">{{ auth()->user()?->name ?? 'Administrator' }}</span>
                    <span class="text-[10px] font-semibold text-slate-500 dark:text-gray-400 truncate tracking-wide">{{ auth()->user()?->email ?? 'admin@as-salam.id' }}</span>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-3 p-3 text-error dark:text-red-400 bg-red-50 dark:bg-red-500/10 hover:bg-red-100 dark:hover:bg-red-500/20 rounded-xl transition-colors duration-200">
                    <span class="material-symbols-outlined">logout</span>
                    <span class="text-sm font-bold font-headline">Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <header class="bg-white/80 dark:bg-[#0a0c0e]/80 backdrop-blur-lg fixed top-0 right-0 w-[calc(100%-16rem)] z-40 border-b border-slate-100 dark:border-white/5 h-16 flex justify-between items-center px-8 transition-colors duration-300">
        <div class="flex items-center gap-2">
            <span class="text-slate-400 text-sm font-headline">Dashboard</span>
            <span class="text-slate-300 dark:text-slate-600 text-sm">/</span>
            <span class="text-emerald-600 dark:text-emerald-400 font-bold font-headline">
                @if(request()->routeIs('dashboard')) Beranda
                @elseif(request()->routeIs('kontrol.manual')) Kontrol Manual
                @elseif(request()->routeIs('jadwal.*')) Jadwal Sholat
                @elseif(request()->routeIs('devices.*')) Manajemen Perangkat
                @elseif(request()->routeIs('notifikasi.*')) Notifikasi
                @elseif(request()->routeIs('pengaturan.*')) Pengaturan
                @elseif(request()->routeIs('riwayat.*')) Riwayat Penyemprotan
                @else Halaman @endif
            </span>
        </div>
        <div class="flex items-center gap-6">
            <button onclick="toggleDarkMode()" class="h-9 w-9 rounded-full flex items-center justify-center bg-slate-100 dark:bg-[#111417] text-slate-600 dark:text-emerald-400 border border-slate-200 dark:border-emerald-500/30 hover:ring-2 hover:ring-emerald-500 transition-all shadow-sm">
                <span class="material-symbols-outlined text-[18px]" id="theme-icon">dark_mode</span>
            </button>

            <a href="{{ route('pengaturan.index') }}" class="h-9 w-9 rounded-full bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center overflow-hidden hover:ring-2 hover:ring-emerald-500 transition-all shadow-sm border-2 border-primary dark:border-emerald-400">
                @if(auth()->check() && auth()->user()->photo)
                    <img src="{{ asset('storage/' . auth()->user()->photo) }}" class="w-full h-full object-cover">
                @else
                    <span class="material-symbols-outlined text-emerald-700 dark:text-emerald-400 text-sm">person</span>
                @endif
            </a>
        </div>
    </header>

    <main class="ml-64 pt-24 pb-12 px-8 min-h-screen">
        @if(session('success') || session('error') || $errors->any())
            <div id="toast-notification" class="fixed top-24 right-8 z-50 w-full max-w-sm rounded-3xl shadow-2xl border border-slate-200/60 dark:border-white/10 bg-white/95 dark:bg-[#111417]/95 backdrop-blur-xl transition duration-300 ease-out opacity-100">
                <div class="flex items-center gap-3 px-5 py-4">
                    <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-2xl">{{ session('success') ? 'check_circle' : 'error' }}</span>
                    <div>
                        <p class="font-bold text-sm text-slate-900 dark:text-gray-100">{{ session('success') ? 'Berhasil' : 'Gagal' }}</p>
                        <p class="text-sm text-slate-600 dark:text-gray-400">{{ session('success') ?? session('error') ?? $errors->first() }}</p>
                    </div>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toast = document.getElementById('toast-notification');
            if (!toast) return;
            setTimeout(() => {
                toast.classList.add('opacity-0');
                setTimeout(() => toast.remove(), 500);
            }, 4000);
        });
    </script>

    <script>
        // Fungsi sinkronisasi tombol Mode Sanctuary di halaman pengaturan jika ada
        function updateSanctuaryButtons(mode) {
            const lightButton = document.getElementById('sanctuary-light');
            const darkButton = document.getElementById('sanctuary-dark');
            
            if (!lightButton || !darkButton) return;

            if (mode === 'dark') {
                darkButton.classList.add('bg-white', 'text-primary', 'dark:bg-emerald-500', 'dark:text-[#0a0c0e]', 'shadow-sm');
                darkButton.classList.remove('text-on-surface-variant', 'hover:text-on-surface', 'dark:text-gray-500', 'dark:hover:text-gray-300');
                lightButton.classList.remove('bg-white', 'text-primary', 'dark:bg-emerald-500', 'dark:text-[#0a0c0e]', 'shadow-sm');
                lightButton.classList.add('text-on-surface-variant', 'hover:text-on-surface', 'dark:text-gray-500', 'dark:hover:text-gray-300');
            } else {
                lightButton.classList.add('bg-white', 'text-primary', 'dark:bg-emerald-500', 'dark:text-[#0a0c0e]', 'shadow-sm');
                lightButton.classList.remove('text-on-surface-variant', 'hover:text-on-surface', 'dark:text-gray-500', 'dark:hover:text-gray-300');
                darkButton.classList.remove('bg-white', 'text-primary', 'dark:bg-emerald-500', 'dark:text-[#0a0c0e]', 'shadow-sm');
                darkButton.classList.add('text-on-surface-variant', 'hover:text-on-surface', 'dark:text-gray-500', 'dark:hover:text-gray-300');
            }
        }

        // Fungsi mengatur tema HTML dan Local Storage
        function setSanctuaryMode(mode) {
            const html = document.documentElement;
            
            if (mode === 'dark') {
                html.classList.add('dark');
                localStorage.setItem('sanctuary_mode', 'dark');
                if (document.getElementById('theme-icon')) {
                    document.getElementById('theme-icon').innerText = 'light_mode';
                }
            } else {
                html.classList.remove('dark');
                localStorage.setItem('sanctuary_mode', 'light');
                if (document.getElementById('theme-icon')) {
                    document.getElementById('theme-icon').innerText = 'dark_mode';
                }
            }
            
            // Perbarui tombol sanctuary di halaman pengaturan
            updateSanctuaryButtons(mode);
            
            // Broadcast event untuk tab lain (opsional)
            window.dispatchEvent(new CustomEvent('sanctuary_mode_changed', { detail: { mode: mode } }));
        }

        // Fungsi toggle dari tombol header
        function toggleDarkMode() {
            const currentMode = localStorage.getItem('sanctuary_mode') || 'light';
            setSanctuaryMode(currentMode === 'dark' ? 'light' : 'dark');
        }

        // Cek Local Storage untuk Tema saat halaman dimuat
        function initSanctuaryMode() {
            const mode = localStorage.getItem('sanctuary_mode') || 'light';
            setSanctuaryMode(mode);
        }

        // Sinkronisasi antar tab browser
        window.addEventListener('storage', function(e) {
            if (e.key === 'sanctuary_mode') {
                setSanctuaryMode(e.newValue || 'light');
            }
        });

        // Jalankan inisialisasi
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initSanctuaryMode);
        } else {
            initSanctuaryMode();
        }
    </script>
</body>
</html>