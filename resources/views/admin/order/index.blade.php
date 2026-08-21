@extends('layouts.admin', ['title' => 'Order'])

@section('content')
    <div class="flex items-center justify-between mb-6">
        <p class="text-gray-600">Daftar semua order. Klik baris untuk melihat detail item.</p>
    </div>

    @if (session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <form method="GET" class="flex flex-wrap items-end gap-3">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Kode order, meja, member..."
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors">
                </div>
                <div class="min-w-[160px]">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-orange-700 transition-colors">
                        Filter
                    </button>
                    @if (request('search') || request('tanggal'))
                        <a href="{{ route('admin.order.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:text-gray-800 border border-gray-300 hover:bg-gray-50 transition-colors">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
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
                        <th class="text-right px-6 py-3 font-medium text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-orange-50/50 transition-colors cursor-pointer"
                            onclick="toggleDetail({{ $loop->index }})">
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
                            <td class="px-6 py-4 text-right" onclick="event.stopPropagation()">
                                <div class="flex items-center justify-end gap-2">
                                    <button onclick="toggleDetail({{ $loop->index }})" class="text-gray-400 hover:text-orange-600 transition-colors" title="Detail">
                                        <svg id="chevron-{{ $loop->index }}" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                    <form action="{{ route('admin.order.destroy', $order) }}" method="POST" onsubmit="return confirm('Yakin hapus order ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        {{-- Detail Order Items --}}
                        <tr id="detail-{{ $loop->index }}" class="hidden bg-orange-50/40">
                            <td colspan="10" class="px-6 pb-5 pt-1">
                                <div class="bg-white rounded-lg border border-orange-100 overflow-hidden">
                                    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                                        <h4 class="text-sm font-semibold text-gray-800">
                                            Detail Order <span class="font-mono text-xs bg-orange-100 text-orange-700 px-2 py-0.5 rounded">{{ $order->kode_order }}</span>
                                        </h4>
                                        <span class="text-xs text-gray-400">Meja {{ $order->meja }} &middot; {{ $order->tanggal->format('d/m/Y') }}</span>
                                    </div>
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-sm">
                                            <thead class="bg-gray-50 border-b border-gray-100">
                                                <tr>
                                                    <th class="text-left px-4 py-2.5 font-medium text-gray-500">Produk</th>
                                                    <th class="text-right px-4 py-2.5 font-medium text-gray-500">Harga</th>
                                                    <th class="text-center px-4 py-2.5 font-medium text-gray-500">Qty</th>
                                                    <th class="text-right px-4 py-2.5 font-medium text-gray-500">Diskon</th>
                                                    <th class="text-right px-4 py-2.5 font-medium text-gray-500">Total</th>
                                                    <th class="text-left px-4 py-2.5 font-medium text-gray-500">Kasir</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-50">
                                                @forelse ($order->items as $item)
                                                    <tr>
                                                        <td class="px-4 py-2.5 font-medium text-gray-800">{{ $item->produk->nama ?? '-' }}</td>
                                                        <td class="px-4 py-2.5 text-right text-gray-600">{{ $item->harga_formatted }}</td>
                                                        <td class="px-4 py-2.5 text-center font-medium text-gray-800">{{ $item->qty }}</td>
                                                        <td class="px-4 py-2.5 text-right text-gray-600">{{ $item->diskon > 0 ? $item->diskon_formatted : '-' }}</td>
                                                        <td class="px-4 py-2.5 text-right font-semibold text-gray-800">{{ $item->total_formatted }}</td>
                                                        <td class="px-4 py-2.5 text-gray-600">{{ $item->kasir->nama ?? '-' }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="6" class="px-4 py-6 text-center text-gray-400">
                                                            Tidak ada item untuk order ini.
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                            @if ($order->items->isNotEmpty())
                                                <tfoot class="bg-gray-50 border-t border-gray-100">
                                                    <tr>
                                                        <td colspan="4" class="px-4 py-2.5 text-right font-medium text-gray-500">Total Harga</td>
                                                        <td class="px-4 py-2.5 text-right font-bold text-orange-600">{{ $order->total_harga_formatted }}</td>
                                                        <td></td>
                                                    </tr>
                                                </tfoot>
                                            @endif
                                        </table>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-12 text-center text-gray-400">
                                Belum ada data order.
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

@push('scripts')
<script>
    function toggleDetail(index) {
        const row = document.getElementById('detail-' + index);
        const chevron = document.getElementById('chevron-' + index);

        row.classList.toggle('hidden');
        chevron.classList.toggle('rotate-180');
    }
</script>
@endpush
