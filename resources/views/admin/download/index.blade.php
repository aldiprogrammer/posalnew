@extends('layouts.admin', ['title' => 'Download APK'])

@section('content')
    <div class="mb-6">
        <p class="text-gray-600">Unduh aplikasi kasir POSAL untuk perangkat Android Anda.</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 lg:p-8 max-w-3xl">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
            <div class="w-16 h-16 shrink-0 rounded-2xl bg-orange-600 text-white flex items-center justify-center shadow-lg shadow-orange-200">
                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M17.6 9.48l1.84-3.18c.16-.31.04-.69-.26-.85a.657.657 0 00-.83.22l-1.88 3.24c-1.15-.48-2.44-.75-3.8-.75s-2.65.27-3.8.75L6.97 5.67a.657.657 0 00-.83-.22c-.3.15-.42.54-.26.85L7.72 9.48C5.38 10.82 3.74 13.2 3.74 16h16.52c0-2.8-1.64-5.18-4-6.52zM8.5 14c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm7 0c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zM12 21c-1.31 0-2.48-.42-3.45-1.13h6.9c-.97.71-2.14 1.13-3.45 1.13z"/>
                </svg>
            </div>
            <div class="flex-1">
                <h2 class="text-xl font-bold text-gray-900">Aplikasi Kasir POSAL (Android)</h2>
                <p class="mt-1 text-sm text-gray-600">Kelola penjualan, stok, dan laporan langsung dari ponsel Android Anda. Gratis, ringan, dan tanpa iklan.</p>
                <div class="mt-3 flex flex-wrap items-center gap-x-6 gap-y-2 text-sm text-gray-500">
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                        Versi 1.0
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8.21 7.89C9.02 6.14 10.39 5 12 5c1.69 0 3.11 1.22 3.89 3.05C17.21 11.4 15.5 13 12 14.5c-3.5-1.5-5.21-3.1-4.39-6.61v0z"/></svg>
                        Android 8.0+
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        {{ $apk['size'] }} MB
                    </span>
                </div>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-gray-100 flex flex-col sm:flex-row items-center gap-4">
            <a href="{{ route('admin.download-apk.download') }}"
               class="inline-flex items-center gap-2 bg-orange-600 text-white px-6 py-3 rounded-lg text-sm font-semibold hover:bg-orange-700 transition-colors w-full sm:w-auto justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Download APK ({{ $apk['size'] }} MB)
            </a>
            <p class="text-xs text-gray-400">File dikirim langsung ke perangkat Anda saat tombol diklik.</p>
        </div>
    </div>
@endsection