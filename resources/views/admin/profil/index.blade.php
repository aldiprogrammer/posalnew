@extends('layouts.admin', ['title' => 'Profil Usaha'])

@section('content')
    <div class="mb-6">
        <p class="text-gray-600">Kelola informasi profil usaha Anda.</p>
    </div>

    @if (session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-2xl">
        <h2 class="text-lg font-semibold text-gray-800 mb-6">Form Profil Usaha</h2>

        @if ($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.profil.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div>
                    <label for="nama_usaha" class="block text-sm font-medium text-gray-700 mb-1">Nama Usaha <span class="text-red-500">*</span></label>
                    <input type="text" id="nama_usaha" name="nama_usaha" value="{{ old('nama_usaha', $profil->nama_usaha) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors"
                           placeholder="Masukkan nama usaha">
                </div>

                <div>
                    <label for="logo" class="block text-sm font-medium text-gray-700 mb-1">Logo Usaha</label>
                    <div class="flex items-center gap-4">
                        <div id="logo-preview-wrapper" class="w-20 h-20 shrink-0 rounded-lg border border-gray-200 bg-gray-50 flex items-center justify-center overflow-hidden">
                            @if ($profil->logo)
                                <img src="{{ $profil->logo_url }}" alt="Logo" class="w-full h-full object-contain">
                            @else
                                <svg id="logo-placeholder" class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <input type="file" id="logo" name="logo" accept=".jpg,.jpeg,.png,.webp,.svg"
                                   class="block w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-orange-600 file:text-white hover:file:bg-orange-700 file:cursor-pointer file:transition-colors">
                            <p class="text-xs text-gray-400 mt-1">Format: JPG, JPEG, PNG, WEBP, SVG. Maksimal 2MB.</p>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="nohp" class="block text-sm font-medium text-gray-700 mb-1">No. HP</label>
                    <input type="text" id="nohp" name="nohp" value="{{ old('nohp', $profil->nohp) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors"
                           placeholder="08xxxxxxxxxx">
                </div>

                <div>
                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    <textarea id="alamat" name="alamat" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors resize-none"
                              placeholder="Masukkan alamat usaha">{{ old('alamat', $profil->alamat) }}</textarea>
                </div>
            </div>

            <div class="flex items-center gap-3 mt-6 pt-4 border-t border-gray-100">
                <button type="submit" class="bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-orange-700 transition-colors">
                    Simpan
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    document.getElementById('logo')?.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        const wrapper = document.getElementById('logo-preview-wrapper');
        let img = wrapper.querySelector('img');

        if (!img) {
            img = document.createElement('img');
            img.alt = 'Logo';
            img.className = 'w-full h-full object-contain';
            wrapper.innerHTML = '';
            wrapper.appendChild(img);
        }

        img.src = URL.createObjectURL(file);
    });
</script>
@endpush
