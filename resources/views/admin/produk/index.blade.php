@extends('layouts.admin', ['title' => 'Produk'])

@section('content')
    <div class="flex items-center justify-between mb-6">
        <p class="text-gray-600">Kelola data produk.</p>
        <button onclick="openModal('modal-create')" class="inline-flex items-center gap-2 bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-orange-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Produk
        </button>
    </div>

    @if (session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left px-6 py-3 font-medium text-gray-500">No</th>
                        <th class="text-left px-6 py-3 font-medium text-gray-500">Kode</th>
                        <th class="text-left px-6 py-3 font-medium text-gray-500">Foto</th>
                        <th class="text-left px-6 py-3 font-medium text-gray-500">Nama</th>
                        <th class="text-left px-6 py-3 font-medium text-gray-500">Kategori</th>
                        @if ($modeSatuan)
                            <th class="text-left px-6 py-3 font-medium text-gray-500">Satuan & Qty All</th>
                            <th class="text-right px-6 py-3 font-medium text-gray-500">Harga Satuan Kecil</th>
                        @else
                            <th class="text-right px-6 py-3 font-medium text-gray-500">Harga</th>
                            <th class="text-right px-6 py-3 font-medium text-gray-500">Diskon</th>
                        @endif
                        <th class="text-center px-6 py-3 font-medium text-gray-500">Stok</th>
                        <th class="text-right px-6 py-3 font-medium text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($produks as $produk)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-gray-500">{{ $produks->firstItem() + $loop->index }}</td>
                            <td class="px-6 py-4">
                                @if ($produk->kode_produk)
                                    <span class="font-mono text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">{{ $produk->kode_produk }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if ($produk->foto)
                                    <img src="{{ $produk->foto_url }}" alt="{{ $produk->nama }}" class="w-10 h-10 rounded-lg object-cover border border-gray-200">
                                @else
                                    <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-800">{{ $produk->nama }}</div>
                                @if ($produk->keterangan)
                                    <div class="text-xs text-gray-400 mt-0.5 max-w-[200px] truncate" title="{{ $produk->keterangan }}">{{ $produk->keterangan }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-700">
                                    {{ $produk->kategori->nama ?? '-' }}
                                </span>
                            </td>
                            @if ($modeSatuan)
                            <td class="px-6 py-4">
                                @if ($produk->qty_all !== null && $produk->satuan_kecil)
                                    <span class="text-sm font-medium text-gray-800">{{ number_format($produk->qty_all, 0, ',', '.') }} {{ $produk->satuan_kecil }}</span>
                                    @if ($produk->qty && $produk->satuan_besar)
                                        <div class="text-xs text-gray-400 mt-0.5">{{ number_format($produk->qty, 0, ',', '.') }} {{ $produk->satuan_besar }}</div>
                                    @endif
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right font-medium text-gray-800">{{ $produk->harga_satuan_kecil !== null ? 'Rp '.number_format($produk->harga_satuan_kecil, 0, ',', '.') : '-' }}</td>
                        @else
                            <td class="px-6 py-4 text-right font-medium text-gray-800">{{ $produk->harga_formatted }}</td>
                            <td class="px-6 py-4 text-right text-gray-600">{{ $produk->diskon > 0 ? $produk->diskon_formatted : '-' }}</td>
                        @endif
                            <td class="px-6 py-4 text-center">
                                @if ($produk->stok === 'tersedia')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Tersedia</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Tidak Tersedia</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button onclick='openEditModal(@json($produk))' class="text-gray-400 hover:text-orange-600 transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <form action="{{ route('admin.produk.destroy', $produk) }}" method="POST" onsubmit="return confirm('Yakin hapus produk ini?')">
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
                            <td colspan="9" class="px-6 py-12 text-center text-gray-400">
                                Belum ada data produk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($produks->hasPages())
            <div class="px-6 py-3 border-t border-gray-100">
                {{ $produks->links() }}
            </div>
        @endif
    </div>

    @php $kategoris = \App\Models\Kategori::orderBy('nama')->get(); @endphp

    {{-- Modal Tambah --}}
    <div id="modal-create" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50" onclick="closeModal('modal-create')"></div>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-lg relative max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 sticky top-0 bg-white">
                    <h3 class="text-lg font-semibold text-gray-800">Tambah Produk</h3>
                    <button onclick="closeModal('modal-create')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="px-6 py-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kode Produk</label>
                            <div class="flex gap-2">
                                <input type="text" id="create-kode_produk" name="kode_produk" value="{{ old('kode_produk') }}" maxlength="50"
                                       class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors font-mono"
                                       placeholder="Kode produk (opsional)">
                                <button type="button" onclick="generateKode('create-kode_produk')" class="inline-flex items-center gap-1 bg-gray-100 text-gray-700 px-3 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors shrink-0" title="Generate kode otomatis">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    Acak
                                </button>
                                <button type="button" onclick="openScanner('create-kode_produk')" class="inline-flex items-center gap-1 bg-orange-100 text-orange-700 px-3 py-2 rounded-lg text-sm font-medium hover:bg-orange-200 transition-colors shrink-0" title="Scan barcode/QR">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                    Scan
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk <span class="text-red-500">*</span></label>
                            <input type="text" name="nama" value="{{ old('nama') }}" required
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors"
                                   placeholder="Masukkan nama produk">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Foto Produk</label>
                            <input type="file" name="foto" accept="image/*"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100"
                                   onchange="previewFoto(this, 'preview-create')">
                            <div id="preview-create" class="mt-2 hidden">
                                <img src="" alt="Preview" class="w-20 h-20 rounded-lg object-cover border border-gray-200">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                            <select name="kategori_id" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors">
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                            <textarea name="keterangan" rows="2"
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors resize-none"
                                      placeholder="Masukkan keterangan">{{ old('keterangan') }}</textarea>
                        </div>
                        @if (!$modeSatuan)
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Harga <span class="text-red-500">*</span></label>
                                <input type="number" name="harga" value="{{ old('harga', 0) }}" required min="0"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors"
                                       placeholder="0">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Diskon</label>
                                <input type="number" name="diskon" value="{{ old('diskon', 0) }}" min="0"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors"
                                       placeholder="0">
                            </div>
                        </div>
                        @endif
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Stok <span class="text-red-500">*</span></label>
                            <div class="flex gap-4">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="stok" value="tersedia" {{ old('stok', 'tersedia') === 'tersedia' ? 'checked' : '' }} required class="text-orange-600 focus:ring-orange-500">
                                    <span class="text-sm text-gray-700">Tersedia</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="stok" value="tidak tersedia" {{ old('stok') === 'tidak tersedia' ? 'checked' : '' }} required class="text-orange-600 focus:ring-orange-500">
                                    <span class="text-sm text-gray-700">Tidak Tersedia</span>
                                </label>
                            </div>
                        </div>
                        @if ($modeSatuan)
                        <div class="border-t border-gray-100 pt-4">
                            <p class="text-sm font-semibold text-gray-700 mb-3">Satuan & Kuantitas</p>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Satuan Besar</label>
                                    <select name="satuan_besar"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors">
                                        <option value="">-- Pilih Satuan --</option>
                                        @foreach ($satuanList as $satuan)
                                            <option value="{{ $satuan }}" @selected(old('satuan_besar') === $satuan)>{{ $satuan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Isi</label>
                                    <input type="number" id="create-isi" name="isi" value="{{ old('isi') }}" min="0" oninput="hitungSatuan('modal-create')"
                                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors"
                                           placeholder="Jumlah isi / satuan besar">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Qty</label>
                                    <input type="number" id="create-qty" name="qty" value="{{ old('qty') }}" min="0" oninput="hitungSatuan('modal-create')"
                                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors"
                                           placeholder="Qty satuan besar">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Satuan Kecil</label>
                                    <select name="satuan_kecil"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors">
                                        <option value="">-- Pilih Satuan --</option>
                                        @foreach ($satuanList as $satuan)
                                            <option value="{{ $satuan }}" @selected(old('satuan_kecil') === $satuan)>{{ $satuan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Satuan Besar</label>
                                    <input type="number" id="create-harga_satuan_besar" name="harga_satuan_besar" value="{{ old('harga_satuan_besar') }}" min="0" oninput="hitungSatuan('modal-create')"
                                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors"
                                           placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Qty All (isi × qty)</label>
                                    <input type="number" id="create-qty_all" name="qty_all" value="{{ old('qty_all') }}" readonly
                                           class="w-full border border-gray-200 bg-gray-50 text-gray-500 rounded-lg px-3 py-2 text-sm font-mono"
                                           placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Satuan Kecil</label>
                                    <input type="number" id="create-harga_satuan_kecil" name="harga_satuan_kecil" value="{{ old('harga_satuan_kecil') }}" min="0"
                                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors"
                                           placeholder="0">
                                </div>
                            </div>
                            <p class="mt-2 text-xs text-gray-400">Qty All otomatis = Isi × Qty.</p>
                        </div>
                        @endif
                    </div>
                    <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100 sticky bottom-0 bg-white">
                        <button type="button" onclick="closeModal('modal-create')" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 transition-colors">Batal</button>
                        <button type="submit" class="bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-orange-700 transition-colors">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div id="modal-edit" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50" onclick="closeModal('modal-edit')"></div>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-lg relative max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 sticky top-0 bg-white">
                    <h3 class="text-lg font-semibold text-gray-800">Edit Produk</h3>
                    <button onclick="closeModal('modal-edit')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form id="form-edit" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="px-6 py-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kode Produk</label>
                            <div class="flex gap-2">
                                <input type="text" id="edit-kode_produk" name="kode_produk" maxlength="50"
                                       class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors font-mono"
                                       placeholder="Kode produk (opsional)">
                                <button type="button" onclick="generateKode('edit-kode_produk')" class="inline-flex items-center gap-1 bg-gray-100 text-gray-700 px-3 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors shrink-0" title="Generate kode otomatis">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    Acak
                                </button>
                                <button type="button" onclick="openScanner('edit-kode_produk')" class="inline-flex items-center gap-1 bg-orange-100 text-orange-700 px-3 py-2 rounded-lg text-sm font-medium hover:bg-orange-200 transition-colors shrink-0" title="Scan barcode/QR">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                    Scan
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk <span class="text-red-500">*</span></label>
                            <input type="text" id="edit-nama" name="nama" required
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors"
                                   placeholder="Masukkan nama produk">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Foto Produk</label>
                            <input type="file" name="foto" accept="image/*"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100"
                                   onchange="previewFoto(this, 'preview-edit')">
                            <div id="preview-edit" class="mt-2 hidden">
                                <img src="" alt="Preview" class="w-20 h-20 rounded-lg object-cover border border-gray-200">
                            </div>
                            <div id="preview-edit-current" class="mt-2 hidden">
                                <p class="text-xs text-gray-400 mb-1">Foto saat ini:</p>
                                <img src="" alt="Current" class="w-20 h-20 rounded-lg object-cover border border-gray-200">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                            <select id="edit-kategori_id" name="kategori_id" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors">
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                            <textarea id="edit-keterangan" name="keterangan" rows="2"
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors resize-none"
                                      placeholder="Masukkan keterangan"></textarea>
                        </div>
                        @if (!$modeSatuan)
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Harga <span class="text-red-500">*</span></label>
                                <input type="number" id="edit-harga" name="harga" required min="0"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors"
                                       placeholder="0">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Diskon</label>
                                <input type="number" id="edit-diskon" name="diskon" min="0"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors"
                                       placeholder="0">
                            </div>
                        </div>
                        @endif
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Stok <span class="text-red-500">*</span></label>
                            <div class="flex gap-4">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" id="edit-stok-t" name="stok" value="tersedia" required class="text-orange-600 focus:ring-orange-500">
                                    <span class="text-sm text-gray-700">Tersedia</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" id="edit-stok-f" name="stok" value="tidak tersedia" required class="text-orange-600 focus:ring-orange-500">
                                    <span class="text-sm text-gray-700">Tidak Tersedia</span>
                                </label>
                            </div>
                        </div>
                        @if ($modeSatuan)
                        <div class="border-t border-gray-100 pt-4">
                            <p class="text-sm font-semibold text-gray-700 mb-3">Satuan & Kuantitas</p>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Satuan Besar</label>
                                    <select id="edit-satuan_besar" name="satuan_besar"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors">
                                        <option value="">-- Pilih Satuan --</option>
                                        @foreach ($satuanList as $satuan)
                                            <option value="{{ $satuan }}">{{ $satuan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Isi</label>
                                    <input type="number" id="edit-isi" name="isi" min="0" oninput="hitungSatuan('modal-edit')"
                                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors"
                                           placeholder="Jumlah isi / satuan besar">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Qty</label>
                                    <input type="number" id="edit-qty" name="qty" min="0" oninput="hitungSatuan('modal-edit')"
                                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors"
                                           placeholder="Qty satuan besar">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Satuan Kecil</label>
                                    <select id="edit-satuan_kecil" name="satuan_kecil"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors">
                                        <option value="">-- Pilih Satuan --</option>
                                        @foreach ($satuanList as $satuan)
                                            <option value="{{ $satuan }}">{{ $satuan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Satuan Besar</label>
                                    <input type="number" id="edit-harga_satuan_besar" name="harga_satuan_besar" min="0" oninput="hitungSatuan('modal-edit')"
                                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors"
                                           placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Qty All (isi × qty)</label>
                                    <input type="number" id="edit-qty_all" name="qty_all" readonly
                                           class="w-full border border-gray-200 bg-gray-50 text-gray-500 rounded-lg px-3 py-2 text-sm font-mono"
                                           placeholder="0">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Satuan Kecil</label>
                                    <input type="number" id="edit-harga_satuan_kecil" name="harga_satuan_kecil" min="0"
                                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors"
                                           placeholder="0">
                                </div>
                            </div>
                            <p class="mt-2 text-xs text-gray-400">Qty All otomatis = Isi × Qty.</p>
                        </div>
                        @endif
                    </div>
                    <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100 sticky bottom-0 bg-white">
                        <button type="button" onclick="closeModal('modal-edit')" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 transition-colors">Batal</button>
                        <button type="submit" class="bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-orange-700 transition-colors">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Scanner --}}
    <div id="modal-scanner" class="fixed inset-0 z-[60] hidden">
        <div class="absolute inset-0 bg-black/50" onclick="closeScanner()"></div>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md relative">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800">Scan Barcode / QR Code</h3>
                    <button onclick="closeScanner()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="p-6">
                    <div id="scanner-reader" class="w-full rounded-lg overflow-hidden"></div>
                    <p class="text-xs text-gray-400 text-center mt-3">Arahkan kamera ke barcode atau QR code</p>
                </div>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                @if (old('nama') && !old('_method'))
                    openModal('modal-create');
                @endif
            });
        </script>
    @endif
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.body.style.overflow = '';
    }

    function previewFoto(input, previewId) {
        const preview = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.querySelector('img').src = e.target.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.classList.add('hidden');
        }
    }

    function generateKode(inputId) {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let kode = 'PRD-';
        for (let i = 0; i < 5; i++) {
            kode += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById(inputId).value = kode;
    }

    function hitungSatuan(modalId) {
        const prefix = modalId === 'modal-create' ? 'create-' : 'edit-';
        const isi = parseInt(document.getElementById(prefix + 'isi').value) || 0;
        const qty = parseInt(document.getElementById(prefix + 'qty').value) || 0;

        document.getElementById(prefix + 'qty_all').value = isi * qty;
    }

    let html5QrCode = null;
    let scannerTargetInput = null;

    function openScanner(targetInputId) {
        scannerTargetInput = targetInputId;
        document.getElementById('modal-scanner').classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        html5QrCode = new Html5Qrcode('scanner-reader');
        html5QrCode.start(
            { facingMode: 'environment' },
            { fps: 10, qrbox: { width: 250, height: 150 } },
            function onScanSuccess(decodedText) {
                document.getElementById(scannerTargetInput).value = decodedText;
                closeScanner();
            },
            function onScanFailure() {}
        ).catch(function(err) {
            alert('Tidak dapat mengakses kamera. Pastikan kamera diizinkan.\n' + err);
            closeScanner();
        });
    }

    function closeScanner() {
        if (html5QrCode && html5QrCode.isScanning) {
            html5QrCode.stop().then(function() {
                html5QrCode.clear();
                html5QrCode = null;
            }).catch(function() {
                html5QrCode = null;
            });
        }
        document.getElementById('modal-scanner').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function openEditModal(produk) {
        const form = document.getElementById('form-edit');
        form.action = '/admin/produk/' + produk.id;

        document.getElementById('edit-kode_produk').value = produk.kode_produk || '';
        document.getElementById('edit-nama').value = produk.nama || '';
        document.getElementById('edit-kategori_id').value = produk.kategori_id || '';
        document.getElementById('edit-keterangan').value = produk.keterangan || '';
        document.getElementById('edit-harga').value = produk.harga || 0;
        document.getElementById('edit-diskon').value = produk.diskon || 0;

        const sBesar = document.getElementById('edit-satuan_besar');
        const sIsi = document.getElementById('edit-isi');
        const sQty = document.getElementById('edit-qty');
        const sHargaBesar = document.getElementById('edit-harga_satuan_besar');
        const sKecil = document.getElementById('edit-satuan_kecil');
        const sQtyAll = document.getElementById('edit-qty_all');
        const sHargaKecil = document.getElementById('edit-harga_satuan_kecil');

        if (sBesar) {
            sBesar.value = produk.satuan_besar || '';
            sIsi.value = produk.isi ?? '';
            sQty.value = produk.qty ?? '';
            sHargaBesar.value = produk.harga_satuan_besar ?? '';
            sKecil.value = produk.satuan_kecil || '';
            sQtyAll.value = produk.qty_all ?? '';
            sHargaKecil.value = produk.harga_satuan_kecil ?? '';
        }

        document.getElementById('edit-stok-t').checked = produk.stok === 'tersedia';
        document.getElementById('edit-stok-f').checked = produk.stok === 'tidak tersedia';

        const previewEdit = document.getElementById('preview-edit');
        const previewCurrent = document.getElementById('preview-edit-current');
        previewEdit.classList.add('hidden');

        if (produk.foto_url) {
            previewCurrent.querySelector('img').src = produk.foto_url;
            previewCurrent.classList.remove('hidden');
        } else {
            previewCurrent.classList.add('hidden');
        }

        openModal('modal-edit');
    }
</script>
@endpush
