@extends('layouts.admin', ['title' => 'Edit Pegawai'])

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.pegawai.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-orange-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-2xl">
        <h2 class="text-lg font-semibold text-gray-800 mb-6">Form Edit Pegawai</h2>

        @if ($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.pegawai.update', $pegawai) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama <span class="text-red-500">*</span></label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama', $pegawai->nama) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors"
                           placeholder="Masukkan nama pegawai">
                </div>

                <div>
                    <label for="jabatan" class="block text-sm font-medium text-gray-700 mb-1">Jabatan <span class="text-red-500">*</span></label>
                    <input type="text" id="jabatan" name="jabatan" value="{{ old('jabatan', $pegawai->jabatan) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors"
                           placeholder="Masukkan jabatan">
                </div>

                <div>
                    <label for="no_wa" class="block text-sm font-medium text-gray-700 mb-1">No. WhatsApp</label>
                    <input type="text" id="no_wa" name="no_wa" value="{{ old('no_wa', $pegawai->no_wa) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors"
                           placeholder="08xxxxxxxxxx">
                </div>

                <div>
                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    <textarea id="alamat" name="alamat" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors resize-none"
                              placeholder="Masukkan alamat">{{ old('alamat', $pegawai->alamat) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="jenis_kelamin" value="Laki-laki" {{ old('jenis_kelamin', $pegawai->jenis_kelamin) === 'Laki-laki' ? 'checked' : '' }} required
                                   class="text-orange-600 focus:ring-orange-500">
                            <span class="text-sm text-gray-700">Laki-laki</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="jenis_kelamin" value="Perempuan" {{ old('jenis_kelamin', $pegawai->jenis_kelamin) === 'Perempuan' ? 'checked' : '' }} required
                                   class="text-orange-600 focus:ring-orange-500">
                            <span class="text-sm text-gray-700">Perempuan</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 mt-6 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.pegawai.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 transition-colors">
                    Batal
                </a>
                <button type="submit" class="bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-orange-700 transition-colors">
                    Perbarui
                </button>
            </div>
        </form>
    </div>
@endsection
