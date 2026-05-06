<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Login | IoT Dashboard</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Inter:wght@400;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "secondary-fixed-dim": "#b9c7e0", "outline-variant": "#bbcabf", "on-secondary-fixed-variant": "#3a485c",
                        "primary": "#006c49", "inverse-surface": "#2d3133", "surface": "#f7f9fb", "on-secondary-fixed": "#0d1c2f",
                        "secondary-container": "#d5e3fd", "surface-variant": "#e0e3e5", "surface-container-low": "#f2f4f6",
                        "inverse-on-surface": "#eff1f3", "on-surface-variant": "#3c4a42", "surface-container-lowest": "#ffffff",
                        "surface-container-high": "#e6e8ea", "secondary-fixed": "#d5e3fd", "on-secondary-container": "#57657b",
                        "on-primary-container": "#00422b", "on-error-container": "#93000a", "surface-container-highest": "#e0e3e5",
                        "secondary": "#515f74", "background": "#f7f9fb", "surface-container": "#eceef0", "primary-container": "#10b981",
                        "on-tertiary-fixed-variant": "#842225", "on-error": "#ffffff", "on-tertiary-container": "#711419",
                        "on-secondary": "#ffffff", "outline": "#6c7a71", "tertiary-fixed": "#ffdad7", "tertiary-container": "#fc7c78",
                        "on-tertiary": "#ffffff", "inverse-primary": "#4edea3", "surface-dim": "#d8dadc", "primary-fixed": "#6ffbbe",
                        "on-tertiary-fixed": "#410005", "on-surface": "#191c1e", "error": "#ba1a1a", "tertiary": "#a43a3a",
                        "surface-bright": "#f7f9fb", "on-primary-fixed-variant": "#005236", "on-background": "#191c1e",
                        "on-primary": "#ffffff", "tertiary-fixed-dim": "#ffb3af", "on-primary-fixed": "#002113", "error-container": "#ffdad6",
                        "surface-tint": "#006c49", "primary-fixed-dim": "#4edea3"
                    },
                    "borderRadius": {
                        "DEFAULT": "1rem", "lg": "2rem", "xl": "3rem", "full": "9999px"
                    },
                    "fontFamily": {
                        "headline": ["Manrope"], "body": ["Inter"], "label": ["Inter"]
                    }
                },
            },
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f7f9fb;
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .signature-gradient {
            background: linear-gradient(135deg, #006c49 0%, #10b981 100%);
        }
        .text-gradient {
            background: linear-gradient(135deg, #006c49 0%, #10b981 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="antialiased text-on-surface">
    <div class="flex min-h-screen">
        
        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-primary">
            <div class="absolute inset-0 z-0">
                <img class="w-full h-full object-cover opacity-60" data-alt="Interior of a modern mosque with minimalist white marble arches, soft sunbeams streaming through geometric windows, and a serene, airy atmosphere." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAwKeN8RjyYg0dXForaHH-Dks5EnspMYndNf6RhtRnP3pNh8RFXBJoIP_GhwXey2M7GqNr1Rvg_ge4E_7EfYLCSBfHEKkO6MrwuGOpC00k5bxAI7klWrY7uGQP0YlrRhob4dOq3UqDZsMkllNk7gjR9JO1tdxag5iYmX354JQDIm-51Kk29yBU437dZ_Ry03D3Us6qLL4kVwLan2EdDsT2U61DNCfrkdzbOhb6KffSVwwH6dJxNx1FN14Z8-ApB_BPZHy22h27GLIA" style=""/>
                <div class="absolute inset-0 bg-gradient-to-t from-primary/80 to-transparent"></div>
            </div>
            <div class="relative z-10 flex flex-col justify-end p-20 w-full h-full">
                <div class="mb-8">
                    <h1 class="font-headline text-5xl font-extrabold text-white tracking-tight mb-4 leading-tight">IOT Dashboard</h1>
                    <p class="font-headline text-2xl text-primary-fixed-dim/90 font-medium tracking-wide">Smart Room & Hygiene Control System</p>
                </div>
                <div class="w-24 h-1 signature-gradient rounded-full"></div>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex flex-col justify-center items-center p-8 md:p-24 bg-surface relative">
            
            <div class="w-full max-w-md">
                <div class="flex flex-col items-center mb-12 text-center">
                    <div class="w-20 h-20 bg-surface-container-lowest rounded-xl flex items-center justify-center mb-6 shadow-sm">
                        <span class="material-symbols-outlined text-4xl text-primary" style='font-variation-settings: "FILL" 1;'>mosque</span>
                    </div>
                    <h2 class="font-headline text-3xl font-bold text-on-surface mb-2">Selamat Datang Kembali</h2>
                    <p class="text-secondary font-body">Silakan masuk untuk mengakses dashboard digital.</p>
                </div>

                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-600 rounded-lg text-sm font-semibold text-center">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="space-y-2">
                        <label class="font-label text-[0.6875rem] font-bold uppercase tracking-widest text-outline ml-1" for="email">Email</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline text-xl">mail</span>
                            <input name="email" class="w-full pl-12 pr-4 py-4 bg-surface-container-lowest border-none rounded-lg focus:ring-2 focus:ring-primary/20 text-on-surface transition-all placeholder:text-outline/50" id="email" placeholder="nama@masjid.id" type="email" required autofocus/>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="font-label text-[0.6875rem] font-bold uppercase tracking-widest text-outline ml-1" for="password">Kata Sandi</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline text-xl">lock</span>
                            <input name="password" class="w-full pl-12 pr-4 py-4 bg-surface-container-lowest border-none rounded-lg focus:ring-2 focus:ring-primary/20 text-on-surface transition-all placeholder:text-outline/50" id="password" placeholder="••••••••" type="password" required/>
                        </div>
                    </div>
                    
                    <button class="w-full py-4 signature-gradient text-white font-headline font-bold rounded-full transition-transform active:scale-[0.98] shadow-sm hover:opacity-95" type="submit">
                        Masuk
                    </button>
                </form>
            </div>
            
            <footer class="mt-auto pt-12">
                <p class="font-label text-[0.6875rem] font-bold uppercase tracking-widest text-outline/60">
                    © 2026 IoT Dashboard. Smart Room & Hygiene Control System.
                </p>
            </footer>
        </div>
    </div>

    <script>
        
    </script>
</body>
</html>