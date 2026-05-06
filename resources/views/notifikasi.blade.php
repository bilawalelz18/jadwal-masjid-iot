@extends('layouts.app')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-on-surface dark:text-gray-100 mb-2 font-headline transition-colors duration-300">Pusat Notifikasi</h1>
        <p class="text-secondary dark:text-gray-400 font-body transition-colors duration-300">Peringatan dan pemberitahuan penting terkait status perangkat IoT.</p>
    </div>

    <div class="space-y-4 max-w-4xl">
        @forelse($notifikasis as $notif)
            @php
                // Logika Pewarnaan Berdasarkan Tipe
                if($notif->tipe == 'warning') {
                    $bg = 'bg-amber-50 dark:bg-amber-500/10';
                    $border = 'border-amber-500 dark:border-amber-400';
                    $iconBg = 'bg-amber-100 dark:bg-amber-500/20';
                    $iconColor = 'text-amber-600 dark:text-amber-400';
                    $titleColor = 'text-amber-900 dark:text-amber-300';
                    $textColor = 'text-amber-800 dark:text-amber-200/80';
                } elseif($notif->tipe == 'success') {
                    $bg = 'bg-emerald-50 dark:bg-emerald-500/10';
                    $border = 'border-emerald-500 dark:border-emerald-400';
                    $iconBg = 'bg-emerald-100 dark:bg-emerald-500/20';
                    $iconColor = 'text-emerald-600 dark:text-emerald-400';
                    $titleColor = 'text-emerald-900 dark:text-emerald-300';
                    $textColor = 'text-emerald-800 dark:text-emerald-200/80';
                } elseif($notif->tipe == 'error') {
                    $bg = 'bg-red-50 dark:bg-red-500/10';
                    $border = 'border-red-500 dark:border-red-400';
                    $iconBg = 'bg-red-100 dark:bg-red-500/20';
                    $iconColor = 'text-red-600 dark:text-red-400';
                    $titleColor = 'text-red-900 dark:text-red-300';
                    $textColor = 'text-red-800 dark:text-red-200/80';
                } else {
                    // Default / Info (Biru)
                    $bg = 'bg-blue-50 dark:bg-blue-500/10';
                    $border = 'border-blue-500 dark:border-blue-400';
                    $iconBg = 'bg-blue-100 dark:bg-blue-500/20';
                    $iconColor = 'text-blue-600 dark:text-blue-400';
                    $titleColor = 'text-blue-900 dark:text-blue-300';
                    $textColor = 'text-blue-800 dark:text-blue-200/80';
                }
            @endphp

            <div class="{{ $bg }} border-l-4 {{ $border }} p-6 rounded-r-xl shadow-sm flex items-start gap-4 transition-colors duration-300">
                <div class="{{ $iconBg }} p-2 rounded-full {{ $iconColor }} shrink-0 transition-colors duration-300">
                    <span class="material-symbols-outlined">{{ $notif->ikon }}</span>
                </div>
                <div class="flex-1">
                    <div class="flex justify-between items-start">
                        <h3 class="font-bold {{ $titleColor }} font-headline transition-colors duration-300">{{ $notif->judul }}</h3>
                        {{-- Waktu akan otomatis terformat menjadi '10 Menit yang lalu' atau sejenisnya --}}
                        <span class="text-xs {{ $iconColor }} font-bold transition-colors duration-300">{{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}</span>
                    </div>
                    <p class="{{ $textColor }} text-sm mt-1 transition-colors duration-300">{{ $notif->pesan }}</p>
                </div>
            </div>
        @empty
            <div class="bg-surface-container-lowest dark:bg-[#111417] p-10 rounded-2xl shadow-sm border border-transparent dark:border-white/5 transition-colors duration-300 flex flex-col items-center justify-center text-center">
                <div class="w-16 h-16 bg-surface-container-low dark:bg-white/5 rounded-full flex items-center justify-center mb-4 text-slate-400 dark:text-gray-500">
                    <span class="material-symbols-outlined text-3xl">notifications_off</span>
                </div>
                <h3 class="text-lg font-bold text-on-surface dark:text-gray-100 mb-1">Belum Ada Notifikasi</h3>
                <p class="text-sm text-secondary dark:text-gray-400">Sistem belum merekam aktivitas atau peringatan apapun saat ini.</p>
            </div>
        @endforelse

        {{-- PAGINASI KUSTOM MANUAL (TANPA FILE BARU) --}}
        @if ($notifikasis->hasPages())
            <div class="px-8 py-6 bg-surface-container-low/30 dark:bg-white/5 border border-surface-container-low dark:border-white/5 rounded-2xl flex flex-col sm:flex-row items-center justify-between gap-4 transition-colors mt-8 shadow-sm">
                <p class="text-xs font-semibold text-on-surface-variant dark:text-gray-400">
                    Menampilkan {{ $notifikasis->firstItem() ?? 0 }} - {{ $notifikasis->lastItem() ?? 0 }} dari {{ $notifikasis->total() }} entri
                </p>
                <div class="flex items-center gap-2">
                    {{-- Tombol Previous --}}
                    @if ($notifikasis->onFirstPage())
                        <span class="h-10 w-10 flex items-center justify-center rounded-full text-slate-400 dark:text-gray-600 cursor-not-allowed">
                            <span class="material-symbols-outlined">chevron_left</span>
                        </span>
                    @else
                        <a href="{{ $notifikasis->previousPageUrl() }}" class="h-10 w-10 flex items-center justify-center rounded-full text-on-surface-variant hover:bg-surface-container-high dark:hover:bg-white/10 transition-colors">
                            <span class="material-symbols-outlined">chevron_left</span>
                        </a>
                    @endif

                    {{-- Angka --}}
                    @for ($i = 1; $i <= $notifikasis->lastPage(); $i++)
                        @if ($i == $notifikasis->currentPage())
                            <span class="h-10 w-10 flex items-center justify-center rounded-full bg-primary dark:bg-emerald-500 text-white dark:text-[#0a0c0e] font-bold text-sm shadow-md">{{ $i }}</span>
                        @else
                            <a href="{{ $notifikasis->url($i) }}" class="h-10 w-10 flex items-center justify-center rounded-full text-on-surface-variant hover:bg-surface-container-high dark:hover:bg-white/10 transition-colors font-bold text-sm">{{ $i }}</a>
                        @endif
                    @endfor

                    {{-- Tombol Next --}}
                    @if ($notifikasis->hasMorePages())
                        <a href="{{ $notifikasis->nextPageUrl() }}" class="h-10 w-10 flex items-center justify-center rounded-full text-on-surface-variant hover:bg-surface-container-high dark:hover:bg-white/10 transition-colors">
                            <span class="material-symbols-outlined">chevron_right</span>
                        </a>
                    @else
                        <span class="h-10 w-10 flex items-center justify-center rounded-full text-slate-400 dark:text-gray-600 cursor-not-allowed">
                            <span class="material-symbols-outlined">chevron_right</span>
                        </span>
                    @endif
                </div>
            </div>
        @endif
        
    </div>
@endsection