@extends('layouts.admin', ['title' => 'Potongan Member'])

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <p class="text-gray-600">Kelola potongan global untuk semua member. Potongan yang aktif berlaku bagi seluruh member yang sudah terdaftar.</p>
        </div>
        <button onclick="openModal('modal-create')" class="inline-flex items-center gap-2 bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-orange-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Potongan
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
                        <th class="text-left px-6 py-3 font-medium text-gray-500">Jenis</th>
                        <th class="text-right px-6 py-3 font-medium text-gray-500">Nominal</th>
                        <th class="text-left px-6 py-3 font-medium text-gray-500">Berlaku</th>
                        <th class="text-left px-6 py-3 font-medium text-gray-500">Berakhir</th>
                        <th class="text-right px-6 py-3 font-medium text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($potongans as $potongan)
                        @php
                            $isActive = $potongan->tanggal_mulai->lte(now()) && $potongan->tanggal_akhir->gte(now());
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-gray-500">{{ $potongans->firstItem() + $loop->index }}</td>
                            <td class="px-6 py-4">
                                @if ($potongan->jenis === 'diskon')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-700">Diskon</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">Rupiah</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right font-medium text-gray-800">{{ $potongan->nominal_formatted }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $potongan->tanggal_mulai->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">
                                <span class="{{ $isActive ? 'text-green-600' : 'text-gray-600' }}">{{ $potongan->tanggal_akhir->format('d/m/Y') }}</span>
                                @if ($isActive)
                                    <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-green-100 text-green-700">Aktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button onclick='openEditModal(@json($potongan))' class="text-gray-400 hover:text-orange-600 transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <form action="{{ route('admin.potongan-member.destroy', $potongan) }}" method="POST" onsubmit="return confirm('Yakin hapus potongan ini?')">
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
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                Belum ada data potongan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($potongans->hasPages())
            <div class="px-6 py-3 border-t border-gray-100">
                {{ $potongans->links() }}
            </div>
        @endif
    </div>

    {{-- Modal Tambah --}}
    <div id="modal-create" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50" onclick="closeModal('modal-create')"></div>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md relative">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800">Tambah Potongan</h3>
                    <button onclick="closeModal('modal-create')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form action="{{ route('admin.potongan-member.store') }}" method="POST">
                    @csrf
                    <div class="px-6 py-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Potongan <span class="text-red-500">*</span></label>
                            <div class="flex gap-4">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="jenis" value="diskon" {{ old('jenis', 'diskon') === 'diskon' ? 'checked' : '' }} required class="text-orange-600 focus:ring-orange-500" onchange="updateLabel(this.value)">
                                    <span class="text-sm text-gray-700">Diskon (%)</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="jenis" value="rupiah" {{ old('jenis') === 'rupiah' ? 'checked' : '' }} required class="text-orange-600 focus:ring-orange-500" onchange="updateLabel(this.value)">
                                    <span class="text-sm text-gray-700">Rupiah (Rp)</span>
                                </label>
                            </div>
                        </div>
                        <div>
                            <label id="label-nominal" class="block text-sm font-medium text-gray-700 mb-1">Diskon (%) <span class="text-red-500">*</span></label>
                            <input type="number" name="nominal" value="{{ old('nominal', 0) }}" required min="0"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors"
                                   placeholder="0">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai <span class="text-red-500">*</span></label>
                                <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', date('Y-m-d')) }}" required
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir <span class="text-red-500">*</span></label>
                                <input type="date" name="tanggal_akhir" value="{{ old('tanggal_akhir') }}" required
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors">
                            </div>
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
                    <h3 class="text-lg font-semibold text-gray-800">Edit Potongan</h3>
                    <button onclick="closeModal('modal-edit')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form id="form-edit" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="px-6 py-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Potongan <span class="text-red-500">*</span></label>
                            <div class="flex gap-4">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" id="edit-jenis-d" name="jenis" value="diskon" required class="text-orange-600 focus:ring-orange-500" onchange="updateLabel(this.value)">
                                    <span class="text-sm text-gray-700">Diskon (%)</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" id="edit-jenis-r" name="jenis" value="rupiah" required class="text-orange-600 focus:ring-orange-500" onchange="updateLabel(this.value)">
                                    <span class="text-sm text-gray-700">Rupiah (Rp)</span>
                                </label>
                            </div>
                        </div>
                        <div>
                            <label id="label-nominal-edit" class="block text-sm font-medium text-gray-700 mb-1">Nominal <span class="text-red-500">*</span></label>
                            <input type="number" id="edit-nominal" name="nominal" required min="0"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors"
                                   placeholder="0">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai <span class="text-red-500">*</span></label>
                                <input type="date" id="edit-tanggal_mulai" name="tanggal_mulai" required
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir <span class="text-red-500">*</span></label>
                                <input type="date" id="edit-tanggal_akhir" name="tanggal_akhir" required
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors">
                            </div>
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
                @if (old('nominal') && !old('_method'))
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

    function updateLabel(jenis) {
        const label = document.getElementById('label-nominal');
        const labelEdit = document.getElementById('label-nominal-edit');
        const text = jenis === 'diskon' ? 'Diskon (%)' : 'Nominal (Rp)';
        if (label) label.innerHTML = text + ' <span class="text-red-500">*</span>';
        if (labelEdit) labelEdit.innerHTML = text + ' <span class="text-red-500">*</span>';
    }

    function openEditModal(potongan) {
        const form = document.getElementById('form-edit');
        form.action = '/admin/potongan-member/' + potongan.id;

        document.getElementById('edit-nominal').value = potongan.nominal || 0;
        document.getElementById('edit-tanggal_mulai').value = potongan.tanggal_mulai ? potongan.tanggal_mulai.substring(0, 10) : '';
        document.getElementById('edit-tanggal_akhir').value = potongan.tanggal_akhir ? potongan.tanggal_akhir.substring(0, 10) : '';

        document.getElementById('edit-jenis-d').checked = potongan.jenis === 'diskon';
        document.getElementById('edit-jenis-r').checked = potongan.jenis === 'rupiah';

        updateLabel(potongan.jenis);
        openModal('modal-edit');
    }
</script>
@endpush
