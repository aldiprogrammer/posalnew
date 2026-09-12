@extends('layouts.admin', ['title' => 'Inventaris'])

@section('content')
    <div class="flex items-center justify-between mb-6">
        <p class="text-gray-600">Kelola data inventaris barang.</p>
        <div class="flex items-center gap-2">
            <button type="button" id="btn-export-label" class="inline-flex items-center gap-2 bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2V8a2 2 0 00-2-2h-2l-2-2H7a2 2 0 00-2 2v2m5 9h2m-2 0a2 2 0 012 2m-2-2a2 2 0 00-2-2H4m18 0a2 2 0 01-2 2m-6 0a2 2 0 01-2-2m2 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-6a2 2 0 012-2h2m8 0h2a2 2 0 012 2"/></svg>
                Export Label
            </button>
            <button onclick="openModal('modal-create')" class="inline-flex items-center gap-2 bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-orange-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Inventaris
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <form id="form-export" action="{{ route('admin.inventaris.export-label') }}" method="POST">
        @csrf
        <div id="form-export-ids"></div>
    </form>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3">
                            <input type="checkbox" id="select-all" class="rounded border-gray-300 accent-orange-600">
                        </th>
                        <th class="text-left px-6 py-3 font-medium text-gray-500">No</th>
                        <th class="text-left px-6 py-3 font-medium text-gray-500">Kode Barang</th>
                        <th class="text-left px-6 py-3 font-medium text-gray-500">Nama Barang</th>
                        <th class="text-left px-6 py-3 font-medium text-gray-500">Kondisi Barang</th>
                        <th class="text-left px-6 py-3 font-medium text-gray-500">Tgl Masuk</th>
                        <th class="text-right px-6 py-3 font-medium text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($inventaris as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <input type="checkbox" name="ids[]" value="{{ $item->id }}" class="row-check rounded border-gray-300 accent-orange-600">
                            </td>
                            <td class="px-6 py-4 text-gray-500">{{ $inventaris->firstItem() + $loop->index }}</td>
                            <td class="px-6 py-4">
                                <span class="font-mono text-sm font-medium text-gray-800 bg-gray-100 px-2.5 py-1 rounded">{{ $item->kode_barang }}</span>
                            </td>
                            <td class="px-6 py-4 text-gray-800 font-medium">{{ $item->nama_barang }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $warna = match ($item->kondisi_barang) {
                                        'Baik' => 'bg-green-100 text-green-700',
                                        'Rusak Ringan' => 'bg-yellow-100 text-yellow-700',
                                        'Rusak Berat' => 'bg-red-100 text-red-700',
                                        default => 'bg-gray-100 text-gray-700',
                                    };
                                @endphp
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium {{ $warna }}">{{ $item->kondisi_barang }}</span>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $item->tgl_masuk->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button onclick="openEditModal({{ $item->toJson() }})" class="text-gray-400 hover:text-orange-600 transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <form action="{{ route('admin.inventaris.destroy', $item) }}" method="POST" onsubmit="return confirm('Yakin hapus inventaris ini?')">
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
                            <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                Belum ada data inventaris.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($inventaris->hasPages())
            <div class="px-6 py-3 border-t border-gray-100">
                {{ $inventaris->links() }}
            </div>
        @endif
    </div>

    {{-- Modal Tambah --}}
    <div id="modal-create" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50" onclick="closeModal('modal-create')"></div>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md relative">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800">Tambah Inventaris</h3>
                    <button onclick="closeModal('modal-create')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form action="{{ route('admin.inventaris.store') }}" method="POST">
                    @csrf
                    <div class="px-6 py-4 space-y-4">
                        @if ($errors->any())
                            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-sm">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kode Barang</label>
                            <input type="text" value="{{ $nextKodeBarang }}" disabled
                                   class="w-full border border-gray-200 bg-gray-50 text-gray-600 rounded-lg px-3 py-2 text-sm font-mono">
                            <p class="mt-1 text-xs text-gray-400">Kode barang dibuat otomatis (BRG-001, BRG-002, dst).</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_barang" value="{{ old('nama_barang') }}" required maxlength="255"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors"
                                   placeholder="Masukkan nama barang">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kondisi Barang <span class="text-red-500">*</span></label>
                            <select name="kondisi_barang" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors">
                                <option value="">-- Pilih Kondisi --</option>
                                <option value="Baik" @selected(old('kondisi_barang') === 'Baik')>Baik</option>
                                <option value="Rusak Ringan" @selected(old('kondisi_barang') === 'Rusak Ringan')>Rusak Ringan</option>
                                <option value="Rusak Berat" @selected(old('kondisi_barang') === 'Rusak Berat')>Rusak Berat</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tgl Masuk <span class="text-red-500">*</span></label>
                            <input type="date" name="tgl_masuk" value="{{ old('tgl_masuk', date('Y-m-d')) }}" required
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors">
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100">
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
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md relative">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800">Edit Inventaris</h3>
                    <button onclick="closeModal('modal-edit')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form id="form-edit" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="px-6 py-4 space-y-4">
                        @if ($errors->any())
                            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-sm">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kode Barang</label>
                            <input type="text" id="edit-kode_barang" disabled readonly
                                   class="w-full border border-gray-200 bg-gray-50 text-gray-400 rounded-lg px-3 py-2 text-sm font-mono">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang <span class="text-red-500">*</span></label>
                            <input type="text" id="edit-nama_barang" name="nama_barang" required maxlength="255"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors"
                                   placeholder="Masukkan nama barang">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kondisi Barang <span class="text-red-500">*</span></label>
                            <select id="edit-kondisi_barang" name="kondisi_barang" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors">
                                <option value="">-- Pilih Kondisi --</option>
                                <option value="Baik">Baik</option>
                                <option value="Rusak Ringan">Rusak Ringan</option>
                                <option value="Rusak Berat">Rusak Berat</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tgl Masuk <span class="text-red-500">*</span></label>
                            <input type="date" id="edit-tgl_masuk" name="tgl_masuk" required
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors">
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100">
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
                    @if (old('_method') === 'PUT')
                    openModal('modal-edit');
                    document.getElementById('form-edit').action = '{{ url("admin/inventaris/" . old("inventaris_id", "")) }}';
                    document.getElementById('edit-kode_barang').value = @js(old('kode_barang', ''));
                    document.getElementById('edit-nama_barang').value = @js(old('nama_barang', ''));
                    document.getElementById('edit-kondisi_barang').value = @js(old('kondisi_barang', ''));
                    document.getElementById('edit-tgl_masuk').value = @js(old('tgl_masuk', ''));
                @elseif(old('nama_barang'))
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

    function openEditModal(item) {
        const form = document.getElementById('form-edit');
        form.action = '/admin/inventaris/' + item.id;

        document.getElementById('edit-kode_barang').value = item.kode_barang || '';
        document.getElementById('edit-nama_barang').value = item.nama_barang || '';
        document.getElementById('edit-kondisi_barang').value = item.kondisi_barang || '';
        document.getElementById('edit-tgl_masuk').value = item.tgl_masuk ? item.tgl_masuk.substring(0, 10) : '';

        openModal('modal-edit');
    }

    const selectAll = document.getElementById('select-all');
    const rowChecks = document.querySelectorAll('.row-check');

    selectAll?.addEventListener('change', () => {
        rowChecks.forEach(cb => cb.checked = selectAll.checked);
    });

    document.getElementById('btn-export-label')?.addEventListener('click', () => {
        const container = document.getElementById('form-export-ids');
        container.innerHTML = '';

        document.querySelectorAll('.row-check:checked').forEach(cb => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = cb.value;
            container.appendChild(input);
        });

        if (!container.childElementCount) {
            alert('Pilih minimal satu barang untuk di-export.');
            return;
        }

        document.getElementById('form-export').submit();
    });
</script>
@endpush