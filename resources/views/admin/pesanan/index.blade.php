@extends('layouts.admin', ['title' => 'Order Items'])

@section('content')
    <div class="flex items-center justify-between mb-6">
        <p class="text-gray-600">Daftar semua order items.</p>
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
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Kode order, produk, kasir..."
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
                        <a href="{{ route('admin.pesanan.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:text-gray-800 border border-gray-300 hover:bg-gray-50 transition-colors">
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
                        <th class="text-left px-6 py-3 font-medium text-gray-500">Produk</th>
                        <th class="text-right px-6 py-3 font-medium text-gray-500">Harga</th>
                        <th class="text-center px-6 py-3 font-medium text-gray-500">Qty</th>
                        <th class="text-right px-6 py-3 font-medium text-gray-500">Diskon</th>
                        <th class="text-left px-6 py-3 font-medium text-gray-500">Tanggal</th>
                        <th class="text-left px-6 py-3 font-medium text-gray-500">Kasir</th>
                        <th class="text-center px-6 py-3 font-medium text-gray-500">Pembayaran</th>
                        <th class="text-right px-6 py-3 font-medium text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($pesanans as $pesanan)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-gray-500">{{ $pesanans->firstItem() + $loop->index }}</td>
                            <td class="px-6 py-4">
                                <span class="font-mono text-xs font-medium bg-gray-100 text-gray-700 px-2 py-1 rounded">{{ $pesanan->kode_order }}</span>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-800">{{ $pesanan->produk->nama ?? '-' }}</td>
                            <td class="px-6 py-4 text-right text-gray-600">{{ $pesanan->harga_formatted }}</td>
                            <td class="px-6 py-4 text-center font-medium text-gray-800">{{ $pesanan->qty }}</td>
                            <td class="px-6 py-4 text-right text-gray-600">{{ $pesanan->diskon > 0 ? $pesanan->diskon_formatted : '-' }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $pesanan->tanggal->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $pesanan->kasir->nama ?? '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                @php $pembayaran = $pesanan->pembayaran; @endphp
                                @if ($pembayaran === 'tunai')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Tunai</span>
                                @elseif ($pembayaran === 'transfer')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">Transfer</span>
                                @elseif ($pembayaran === 'qris')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-700">QRIS</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button onclick="openEditModal({{ json_encode($pesanan->only('id','kode_order','produk_id','harga','qty','diskon','tanggal','kasir_id','pembayaran')) }})" class="text-gray-400 hover:text-orange-600 transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <form action="{{ route('admin.pesanan.destroy', $pesanan) }}" method="POST" onsubmit="return confirm('Yakin hapus pesanan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-12 text-center text-gray-400">
                                Belum ada data order items.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($pesanans->hasPages())
            <div class="px-6 py-3 border-t border-gray-100">
                {{ $pesanans->links() }}
            </div>
        @endif
    </div>

    @php
        $produks = \App\Models\Produk::orderBy('nama')->get();
        $kasirs = \App\Models\Pengguna::orderBy('nama')->get();
    @endphp

    {{-- Modal Edit --}}
    <div id="modal-edit" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50" onclick="closeModal('modal-edit')"></div>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-lg relative max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 sticky top-0 bg-white">
                    <h3 class="text-lg font-semibold text-gray-800">Edit Order Item</h3>
                    <button onclick="closeModal('modal-edit')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form id="form-edit" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="px-6 py-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kode Order</label>
                            <input type="text" id="edit-kode_order" name="kode_order" readonly
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-50 text-gray-500 cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Produk <span class="text-red-500">*</span></label>
                            <select id="edit-produk_id" name="produk_id" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors">
                                <option value="">Pilih Produk</option>
                                @foreach ($produks as $produk)
                                    <option value="{{ $produk->id }}">{{ $produk->nama }} — {{ 'Rp ' . number_format($produk->harga, 0, ',', '.') }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Harga <span class="text-red-500">*</span></label>
                                <input type="number" id="edit-harga" name="harga" required min="0"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors"
                                       placeholder="0">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Qty <span class="text-red-500">*</span></label>
                                <input type="number" id="edit-qty" name="qty" required min="1"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors"
                                       placeholder="1">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Diskon <span class="text-red-500">*</span></label>
                                <input type="number" id="edit-diskon" name="diskon" required min="0"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors"
                                       placeholder="0">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal <span class="text-red-500">*</span></label>
                                <input type="date" id="edit-tanggal" name="tanggal" required
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kasir <span class="text-red-500">*</span></label>
                            <select id="edit-kasir_id" name="kasir_id" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors">
                                <option value="">Pilih Kasir</option>
                                @foreach ($kasirs as $kasir)
                                    <option value="{{ $kasir->id }}">{{ $kasir->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pembayaran</label>
                            <select id="edit-pembayaran" name="pembayaran"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors">
                                <option value="">- Belum Ada -</option>
                                <option value="tunai">Tunai</option>
                                <option value="transfer">Transfer</option>
                                <option value="qris">QRIS</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100 sticky bottom-0 bg-white">
                        <button type="button" onclick="closeModal('modal-edit')" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 transition-colors">Batal</button>
                        <button type="submit" class="bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-orange-700 transition-colors">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.body.style.overflow = '';
    }

    function openEditModal(pesanan) {
        const form = document.getElementById('form-edit');
        form.action = '/admin/pesanan/' + pesanan.id;

        document.getElementById('edit-kode_order').value = pesanan.kode_order || '';
        document.getElementById('edit-produk_id').value = pesanan.produk_id || '';
        document.getElementById('edit-harga').value = pesanan.harga || 0;
        document.getElementById('edit-qty').value = pesanan.qty || 1;
        document.getElementById('edit-diskon').value = pesanan.diskon || 0;

        const tanggal = pesanan.tanggal ? pesanan.tanggal.substring(0, 10) : '';
        document.getElementById('edit-tanggal').value = tanggal;

        document.getElementById('edit-kasir_id').value = pesanan.kasir_id || '';
        document.getElementById('edit-pembayaran').value = pesanan.pembayaran || '';

        openModal('modal-edit');
    }
</script>
@endpush
