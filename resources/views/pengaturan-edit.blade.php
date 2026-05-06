@extends('layouts.app')

@section('content')
<style>
    .headline { font-family: 'Manrope', sans-serif; }
    .font-label { font-family: 'Inter', sans-serif; }
    .signature-gradient { background: linear-gradient(135deg, #006c49 0%, #10b981 100%); }
</style>

<div class="mt-4 max-w-3xl mx-auto space-y-8 pointer-events-none opacity-50">
    <div class="flex justify-between items-end">
        <div>
            <h2 class="text-3xl font-bold headline text-on-surface dark:text-gray-100">Pengaturan Sistem</h2>
            <p class="text-secondary dark:text-gray-400 font-medium mt-1">Konfigurasi Profil Admini Dashboard.</p>
        </div>
    </div>
    <div class="bg-surface-container-lowest dark:bg-[#111417] border border-transparent dark:border-white/5 p-8 rounded-xl space-y-6 shadow-sm transition-colors duration-300">
        <h3 class="text-lg font-bold headline dark:text-gray-100">Profil Administrator</h3>
        <div class="flex items-center gap-6 p-4 rounded-lg bg-surface-container-low dark:bg-white/5 transition-colors duration-300">
            <div class="w-16 h-16 rounded-full bg-slate-200 dark:bg-[#0a0c0e]"></div>
            <div>
                <h4 class="font-bold text-on-surface dark:text-gray-100">{{ auth()->user()?->name }}</h4>
                <p class="text-sm text-secondary dark:text-gray-400">{{ auth()->user()?->email }}</p>
            </div>
        </div>
    </div>
</div>

<div class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-on-surface/40 dark:bg-black/60 backdrop-blur-sm transition-colors duration-300"></div>
    
    <div class="relative bg-surface-container-lowest dark:bg-[#111417] border border-transparent dark:border-white/5 w-full max-w-lg rounded-xl overflow-hidden shadow-2xl animate-in fade-in zoom-in duration-300 mt-10 max-h-[90vh] overflow-y-auto transition-colors">
        
        <div class="p-8 pb-4 sticky top-0 bg-surface-container-lowest dark:bg-[#111417] z-10 border-b border-slate-100 dark:border-white/5 transition-colors duration-300">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold headline text-on-surface dark:text-gray-100">Edit Profil & Keamanan</h2>
                <a href="{{ route('pengaturan.index') }}" class="p-2 rounded-full hover:bg-surface-container-low dark:hover:bg-white/10 text-secondary dark:text-gray-400 transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </a>
            </div>
        </div>

        <form action="{{ route('pengaturan.update') }}" method="POST" enctype="multipart/form-data" class="p-8 pt-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="flex flex-col items-center justify-center mb-6">
                <div class="relative group cursor-pointer" onclick="document.getElementById('photo_upload').click()">
                    <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-surface-container-low dark:border-[#0a0c0e] shadow-sm transition-colors duration-300">
                        <img id="photo_preview" src="{{ auth()->user()?->photo ? asset('storage/' . auth()->user()->photo) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()?->name ?? 'Admin') . '&color=006c49&background=e0f2fe' }}" class="w-full h-full object-cover"/>
                    </div>
                    <div class="absolute inset-0 bg-black/40 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <span class="material-symbols-outlined text-white">photo_camera</span>
                    </div>
                    <input type="file" id="photo_upload" name="photo" class="hidden" accept="image/jpeg, image/png, image/jpg" onchange="previewImage(this)"/>
                </div>
                <p class="text-[10px] font-bold text-slate-400 dark:text-gray-500 mt-2 uppercase tracking-widest transition-colors duration-300">Ketuk untuk ubah</p>
            </div>

            <div class="space-y-4">
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-primary dark:text-emerald-400 uppercase tracking-widest font-label transition-colors duration-300">Nama Lengkap</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 dark:text-gray-500 text-lg transition-colors duration-300">person</span>
                        <input name="name" class="w-full bg-surface-container-low dark:bg-white/5 border-none rounded-full py-3.5 pl-12 pr-6 text-on-surface dark:text-gray-100 font-bold focus:ring-2 focus:ring-primary/20 dark:focus:ring-emerald-500/50 transition-all text-sm" type="text" value="{{ old('name', auth()->user()?->name) }}" required/>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-primary dark:text-emerald-400 uppercase tracking-widest font-label transition-colors duration-300">Email</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 dark:text-gray-500 text-lg transition-colors duration-300">mail</span>
                        <input name="email" class="w-full bg-surface-container-low dark:bg-white/5 border-none rounded-full py-3.5 pl-12 pr-6 text-on-surface dark:text-gray-100 font-bold focus:ring-2 focus:ring-primary/20 dark:focus:ring-emerald-500/50 transition-all text-sm" type="email" value="{{ old('email', auth()->user()?->email) }}" required/>
                    </div>
                </div>

                <div class="pt-4 space-y-4">
                    <div class="flex items-center gap-2">
                        <div class="h-[1px] flex-1 bg-surface-variant dark:bg-white/10 transition-colors duration-300"></div>
                        <span class="text-[10px] font-bold text-secondary dark:text-gray-500 uppercase tracking-widest transition-colors duration-300">Keamanan Akun (Opsional)</span>
                        <div class="h-[1px] flex-1 bg-surface-variant dark:bg-white/10 transition-colors duration-300"></div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-primary dark:text-emerald-400 uppercase tracking-widest font-label transition-colors duration-300">Sandi Saat Ini</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 dark:text-gray-500 text-lg transition-colors duration-300">lock_open</span>
                            <input name="current_password" class="w-full bg-surface-container-low dark:bg-white/5 border-none rounded-full py-3.5 pl-12 pr-6 text-on-surface dark:text-gray-100 dark:placeholder-gray-500 font-semibold focus:ring-2 focus:ring-primary/20 dark:focus:ring-emerald-500/50 transition-all text-sm @error('current_password') ring-2 ring-red-500 @enderror" placeholder="Kosongkan jika tidak diubah" type="password"/>
                        </div>
                        @error('current_password')
                            <p class="text-xs text-red-500 dark:text-red-400 font-bold mt-1 ml-4">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-primary dark:text-emerald-400 uppercase tracking-widest font-label transition-colors duration-300">Sandi Baru</label>
                            <input name="password" class="w-full bg-surface-container-low dark:bg-white/5 border-none rounded-full py-3.5 px-6 text-on-surface dark:text-gray-100 dark:placeholder-gray-500 font-semibold focus:ring-2 focus:ring-primary/20 dark:focus:ring-emerald-500/50 transition-all text-sm @error('password') ring-2 ring-red-500 @enderror" placeholder="Minimal 8 karakter" type="password"/>
                            @error('password')
                                <p class="text-xs text-red-500 dark:text-red-400 font-bold mt-1 ml-4">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-primary dark:text-emerald-400 uppercase tracking-widest font-label transition-colors duration-300">Konfirmasi Sandi</label>
                            <input name="password_confirmation" class="w-full bg-surface-container-low dark:bg-white/5 border-none rounded-full py-3.5 px-6 text-on-surface dark:text-gray-100 dark:placeholder-gray-500 font-semibold focus:ring-2 focus:ring-primary/20 dark:focus:ring-emerald-500/50 transition-all text-sm @error('password_confirmation') ring-2 ring-red-500 @enderror" placeholder="Ulangi sandi" type="password"/>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-6 flex items-center justify-end gap-3 border-t border-slate-100 dark:border-white/5 transition-colors duration-300">
                <a href="{{ route('pengaturan.index') }}" class="px-6 py-2.5 rounded-full text-secondary dark:text-gray-400 font-bold text-sm hover:bg-surface-container-low dark:hover:bg-white/10 dark:hover:text-gray-200 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-full signature-gradient text-white font-bold text-sm shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Script untuk memunculkan preview gambar saat dipilih
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('photo_preview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection