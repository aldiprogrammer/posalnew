@extends('layouts.admin', ['title' => 'Dashboard'])

@section('content')
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Transaksi</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($totalTransaksi, 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Pendapatan Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-green-100 text-green-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Produk</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($totalProduk, 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Stok Menipis</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($stokMenipis, 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-red-100 text-red-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-1">Grafik Penjualan Hari Ini</h2>
            <p class="text-sm text-gray-400 mb-6">Total per jam ({{ \Illuminate\Support\Carbon::today()->translatedFormat('d M Y') }})</p>

            <div class="flex items-end gap-1 h-44">
                @foreach ($penjualanPerJam as $jam => $total)
                    <div class="relative flex-1 flex flex-col justify-end group h-full">
                        <div class="absolute -top-1 left-1/2 -translate-x-1/2 -translate-y-full hidden group-hover:block bg-gray-800 text-white text-[10px] rounded px-1.5 py-0.5 whitespace-nowrap z-10 shadow">
                            {{ $jam }} · {{ $total > 0 ? 'Rp '.number_format($total, 0, ',', '.') : '-' }}
                        </div>
                        <div class="w-full rounded-t bg-orange-500 transition-colors group-hover:bg-orange-600 {{ $total > 0 ?: 'bg-gray-100 group-hover:bg-gray-100' }}" style="height: {{ max(2, round($total / $maxJam * 100)) }}%"></div>
                    </div>
                @endforeach
            </div>
            <div class="flex gap-1 mt-2">
                @foreach ($penjualanPerJam as $jam => $total)
                    <span class="flex-1 text-center text-[10px] text-gray-400">{{ $loop->index % 3 === 0 ? substr($jam, 0, 2) : '' }}</span>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-1">Grafik Penjualan Per Bulan</h2>
            <p class="text-sm text-gray-400 mb-6">Total 12 bulan terakhir</p>

            <div class="flex items-end gap-1.5 h-44">
                @foreach ($penjualanPerBulan as $bulan => $total)
                    <div class="relative flex-1 flex flex-col justify-end group h-full">
                        <div class="absolute -top-1 left-1/2 -translate-x-1/2 -translate-y-full hidden group-hover:block bg-gray-800 text-white text-[10px] rounded px-1.5 py-0.5 whitespace-nowrap z-10 shadow">
                            {{ $bulan }} · {{ $total > 0 ? 'Rp '.number_format($total, 0, ',', '.') : '-' }}
                        </div>
                        <div class="w-full rounded-t bg-green-500 transition-colors group-hover:bg-green-600 {{ $total > 0 ?: 'bg-gray-100 group-hover:bg-gray-100' }}" style="height: {{ max(2, round($total / $maxBulan * 100)) }}%"></div>
                    </div>
                @endforeach
            </div>
            <div class="flex gap-1.5 mt-2">
                @foreach ($penjualanPerBulan as $bulan => $total)
                    <span class="flex-1 text-center text-[10px] text-gray-400">{{ $bulan }}</span>
                @endforeach
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Selamat Datang</h2>
        <p class="text-gray-600">
            Panel admin POS System. Gunakan menu di sebelah kiri untuk mengelola data.
        </p>
    </div>
@endsection