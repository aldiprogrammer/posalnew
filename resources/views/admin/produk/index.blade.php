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
                        <th class="text-left px-6 py-3 font-medium text-gray-500">Foto</th>
                        <th class="text-left px-6 py-3 font-medium text-gray-500">Nama</th>
                        <th class="text-left px-6 py-3 font-medium text-gray-500">Kategori</th>
                        <th class="text-right px-6 py-3 font-medium text-gray-500">Harga</th>
                        <th class="text-right px-6 py-3 font-medium text-gray-500">Diskon</th>
                        <th class="text-center px-6 py-3 font-medium text-gray-500">Stok</th>
                        <th class="text-right px-6 py-3 font-medium text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($produks as $produk)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-gray-500">{{ $produks->firstItem() + $loop->index }}</td>
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
                            <td class="px-6 py-4 text-right font-medium text-gray-800">{{ $produk->harga_formatted }}</td>
                            <td class="px-6 py-4 text-right text-gray-600">{{ $produk->diskon > 0 ? $produk->diskon_formatted : '-' }}</td>
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
                            <td colspan="8" class="px-6 py-12 text-center text-gray-400">
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

    @php $kategoris = \App\Models\kategori::orderBy('nama')->get(); @endphp

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
                    </div>
                    <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100 sticky bottom-0 bg-white">
                        <button type="button" onclick="closeModal('modal-edit')" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 transition-colors">Batal</button>
                        <button type="submit" class="bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-orange-700 transition-colors">Perbarui</button>
                    </div>
                </form>
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

    function openEditModal(produk) {
        const form = document.getElementById('form-edit');
        form.action = '/admin/produk/' + produk.id;

        document.getElementById('edit-nama').value = produk.nama || '';
        document.getElementById('edit-kategori_id').value = produk.kategori_id || '';
        document.getElementById('edit-keterangan').value = produk.keterangan || '';
        document.getElementById('edit-harga').value = produk.harga || 0;
        document.getElementById('edit-diskon').value = produk.diskon || 0;

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
