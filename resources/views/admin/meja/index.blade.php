@extends('layouts.admin', ['title' => 'Meja'])

@section('content')
    <div class="flex items-center justify-between mb-6">
        <p class="text-gray-600">Kelola data meja.</p>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.meja.export-pdf') }}" target="_blank" class="inline-flex items-center gap-2 bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Export PDF
            </a>
            <button onclick="openModal('modal-create')" class="inline-flex items-center gap-2 bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-orange-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Meja
            </button>
        </div>
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
                        <th class="text-left px-6 py-3 font-medium text-gray-500">No Meja</th>
                        <th class="text-left px-6 py-3 font-medium text-gray-500">Tanggal</th>
                        <th class="text-right px-6 py-3 font-medium text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($mejas as $meja)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-gray-500">{{ $mejas->firstItem() + $loop->index }}</td>
                            <td class="px-6 py-4">
                                <span class="font-mono text-sm font-medium text-gray-800 bg-gray-100 px-2.5 py-1 rounded">{{ $meja->no_meja }}</span>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $meja->tanggal->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button onclick="openEditModal({{ $meja->toJson() }})" class="text-gray-400 hover:text-orange-600 transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <form action="{{ route('admin.meja.destroy', $meja) }}" method="POST" onsubmit="return confirm('Yakin hapus meja ini?')">
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
                            <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                                Belum ada data meja.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($mejas->hasPages())
            <div class="px-6 py-3 border-t border-gray-100">
                {{ $mejas->links() }}
            </div>
        @endif
    </div>

    {{-- Modal Tambah --}}
    <div id="modal-create" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50" onclick="closeModal('modal-create')"></div>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md relative">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800">Tambah Meja</h3>
                    <button onclick="closeModal('modal-create')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form action="{{ route('admin.meja.store') }}" method="POST">
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
                            <label class="block text-sm font-medium text-gray-700 mb-1">No Meja <span class="text-red-500">*</span></label>
                            <input type="text" name="no_meja" value="{{ old('no_meja') }}" required maxlength="20"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors font-mono"
                                   placeholder="Masukkan no meja">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required
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
                    <h3 class="text-lg font-semibold text-gray-800">Edit Meja</h3>
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
                            <label class="block text-sm font-medium text-gray-700 mb-1">No Meja <span class="text-red-500">*</span></label>
                            <input type="text" id="edit-no_meja" name="no_meja" required maxlength="20"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors font-mono"
                                   placeholder="Masukkan no meja">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal <span class="text-red-500">*</span></label>
                            <input type="date" id="edit-tanggal" name="tanggal" required
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
                    document.getElementById('form-edit').action = '{{ url("admin/meja/" . old("meja_id", "")) }}';
                    document.getElementById('edit-no_meja').value = @js(old('no_meja', ''));
                    document.getElementById('edit-tanggal').value = @js(old('tanggal', ''));
                @elseif(old('no_meja'))
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

    function openEditModal(meja) {
        const form = document.getElementById('form-edit');
        form.action = '/admin/meja/' + meja.id;

        document.getElementById('edit-no_meja').value = meja.no_meja || '';
        document.getElementById('edit-tanggal').value = meja.tanggal ? meja.tanggal.substring(0, 10) : '';

        openModal('modal-edit');
    }
</script>
@endpush
