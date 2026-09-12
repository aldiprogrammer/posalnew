@extends('layouts.admin', ['title' => 'Laporan Order'])

@section('content')
    @php
        $namaBulan = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        if ($periode === 'hari_ini') {
            $labelPeriode = 'Hari Ini — ' . today()->format('d/m/Y');
        } elseif ($periode === 'bulan_ini') {
            $labelPeriode = 'Bulan Ini — ' . $namaBulan[now()->month] . ' ' . now()->year;
        } elseif ($periode === 'bulan' && $bulan) {
            [$tahun, $nomorBulan] = explode('-', $bulan);
            $labelPeriode = 'Bulan ' . $namaBulan[(int) $nomorBulan] . ' ' . $tahun;
        } else {
            $labelPeriode = 'Semua Periode';
        }

        $rataRata = $statistik->jumlah_order > 0
            ? (int) round($statistik->total_penjualan / $statistik->jumlah_order)
            : 0;
    @endphp

    <div class="flex items-center justify-between mb-6">
        <p class="text-gray-600">Laporan order per periode.</p>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.laporan-order.export-pdf', ['periode' => $periode, 'bulan' => $bulan]) }}" target="_blank"
               class="inline-flex items-center gap-2 bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                Export PDF
            </a>
            <a href="{{ route('admin.laporan-order.export-excel', ['periode' => $periode, 'bulan' => $bulan]) }}"
               class="inline-flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Export Excel
            </a>
        </div>
    </div>

    {{-- Filter Periode --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.laporan-order.index', ['periode' => 'semua']) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $periode === 'semua' ? 'bg-orange-600 text-white' : 'bg-gray-50 text-gray-600 border border-gray-200 hover:bg-gray-100' }}">
                Semua
            </a>
            <a href="{{ route('admin.laporan-order.index', ['periode' => 'hari_ini']) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $periode === 'hari_ini' ? 'bg-orange-600 text-white' : 'bg-gray-50 text-gray-600 border border-gray-200 hover:bg-gray-100' }}">
                Hari Ini
            </a>
            <a href="{{ route('admin.laporan-order.index', ['periode' => 'bulan_ini']) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $periode === 'bulan_ini' ? 'bg-orange-600 text-white' : 'bg-gray-50 text-gray-600 border border-gray-200 hover:bg-gray-100' }}">
                Bulan Ini
            </a>

            <form method="GET" class="flex items-center gap-2 sm:ml-auto">
                <input type="hidden" name="periode" value="bulan">
                <input type="month" name="bulan" value="{{ $bulan }}" required
                       class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors">
                <button type="submit" class="bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-orange-700 transition-colors">
                    Terapkan
                </button>
            </form>
        </div>
    </div>

    {{-- Kartu Ringkasan --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 px-6 py-5">
            <p class="text-xs font-medium text-gray-500">Jumlah Order</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($statistik->jumlah_order, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 px-6 py-5">
            <p class="text-xs font-medium text-gray-500">Total Penjualan</p>
            <p class="text-2xl font-bold text-orange-600 mt-1">Rp {{ number_format($statistik->total_penjualan, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 px-6 py-5">
            <p class="text-xs font-medium text-gray-500">Total Diskon</p>
            <p class="text-2xl font-bold text-red-500 mt-1">Rp {{ number_format($statistik->total_diskon, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 px-6 py-5">
            <p class="text-xs font-medium text-gray-500">Rata-rata per Order</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">Rp {{ number_format($rataRata, 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- Tabel Laporan --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-800">Daftar Order</h3>
            <span class="text-xs font-medium bg-orange-50 text-orange-700 border border-orange-200 px-3 py-1 rounded-full">
                Periode: {{ $labelPeriode }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left px-6 py-3 font-medium text-gray-500">No</th>
                        <th class="text-left px-6 py-3 font-medium text-gray-500">Kode Order</th>
                        <th class="text-left px-6 py-3 font-medium text-gray-500">Tanggal</th>
                        <th class="text-center px-6 py-3 font-medium text-gray-500">Jumlah Item</th>
                        <th class="text-right px-6 py-3 font-medium text-gray-500">Total Harga</th>
                        <th class="text-right px-6 py-3 font-medium text-gray-500">Diskon</th>
                        <th class="text-left px-6 py-3 font-medium text-gray-500">Member</th>
                        <th class="text-center px-6 py-3 font-medium text-gray-500">No Meja</th>
                        <th class="text-center px-6 py-3 font-medium text-gray-500">Status Cetak</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-gray-500">{{ $orders->firstItem() + $loop->index }}</td>
                            <td class="px-6 py-4">
                                <span class="font-mono text-xs font-medium bg-gray-100 text-gray-700 px-2 py-1 rounded">{{ $order->kode_order }}</span>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $order->tanggal->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 text-center font-medium text-gray-800">{{ $order->jumlah_item }}</td>
                            <td class="px-6 py-4 text-right font-semibold text-gray-800">{{ $order->total_harga_formatted }}</td>
                            <td class="px-6 py-4 text-right text-gray-600">{{ $order->diskon > 0 ? $order->diskon_formatted : '-' }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $order->member->nama ?? 'Non Member' }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-blue-50 text-blue-700 border border-blue-200 px-2 py-0.5 rounded-full text-xs font-medium">{{ $order->meja }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($order->status_cetak)
                                    <span class="inline-flex items-center gap-1 bg-green-50 text-green-700 border border-green-200 px-2 py-0.5 rounded-full text-xs font-medium">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Dicetak
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-500 border border-gray-200 px-2 py-0.5 rounded-full text-xs font-medium">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Belum Dicetak
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-gray-400">
                                Tidak ada order pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($orders->hasPages())
            <div class="px-6 py-3 border-t border-gray-100">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
@endsection
