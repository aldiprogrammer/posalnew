<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - POS Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-orange-500 via-orange-600 to-orange-700 font-sans antialiased flex items-center justify-center p-4 py-10">

    <div class="w-full max-w-2xl">
        {{-- Logo --}}
        <div class="flex flex-col items-center mb-6">
            <div class="w-14 h-14 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center font-bold text-2xl text-white mb-3">P</div>
            <h1 class="text-2xl font-bold text-white tracking-wide">POS Admin</h1>
            <p class="text-orange-100 text-sm mt-1">Daftarkan toko/cafe Anda</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-800">Register</h2>
                <p class="text-sm text-gray-500 mt-0.5">Lengkapi data di bawah untuk membuat akun</p>
            </div>

            <form action="{{ route('register.store') }}" method="POST" class="px-8 py-6 space-y-4">
                @csrf

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                        <ul class="list-disc list-inside space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Nama & Username --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                               class="w-full border rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors @error('name') border-red-400 @else border-gray-300 @enderror"
                               placeholder="Budi Santoso">
                        @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username <span class="text-red-500">*</span></label>
                        <input type="text" id="username" name="username" value="{{ old('username') }}" required
                               class="w-full border rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors @error('username') border-red-400 @else border-gray-300 @enderror"
                               placeholder="budi_santoso">
                        @error('username')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Email & No WA --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                               class="w-full border rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors @error('email') border-red-400 @else border-gray-300 @enderror"
                               placeholder="budi@email.com">
                        @error('email')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="no_wa" class="block text-sm font-medium text-gray-700 mb-1">No. WhatsApp <span class="text-red-500">*</span></label>
                        <input type="text" id="no_wa" name="no_wa" value="{{ old('no_wa') }}" required
                               class="w-full border rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors @error('no_wa') border-red-400 @else border-gray-300 @enderror"
                               placeholder="081234567890">
                        @error('no_wa')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Nama Store & Jenis Usaha --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nama_store" class="block text-sm font-medium text-gray-700 mb-1">Nama Store / Cafe <span class="text-red-500">*</span></label>
                        <input type="text" id="nama_store" name="nama_store" value="{{ old('nama_store') }}" required
                               class="w-full border rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors @error('nama_store') border-red-400 @else border-gray-300 @enderror"
                               placeholder="Warung Kopi Senja">
                        @error('nama_store')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="jenis_usaha" class="block text-sm font-medium text-gray-700 mb-1">Jenis Usaha <span class="text-red-500">*</span></label>
                        <select id="jenis_usaha" name="jenis_usaha" required
                                class="w-full border rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors bg-white @error('jenis_usaha') border-red-400 @else border-gray-300 @enderror">
                            <option value="" disabled {{ old('jenis_usaha') ? '' : 'selected' }}>-- Pilih Jenis Usaha --</option>
                            @foreach ($jenisUsaha as $jenis)
                                <option value="{{ $jenis }}" @selected(old('jenis_usaha') === $jenis)>{{ $jenis }}</option>
                            @endforeach
                        </select>
                        @error('jenis_usaha')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Alamat --}}
                <div>
                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat <span class="text-red-500">*</span></label>
                    <textarea id="alamat" name="alamat" rows="2" required
                              class="w-full border rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors resize-none @error('alamat') border-red-400 @else border-gray-300 @enderror"
                              placeholder="Jl. Merdeka No. 123, Jakarta">{{ old('alamat') }}</textarea>
                    @error('alamat')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Password & Konfirmasi --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                        <input type="password" id="password" name="password" required
                               class="w-full border rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors @error('password') border-red-400 @else border-gray-300 @enderror"
                               placeholder="Minimal 8 karakter">
                        @error('password')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password <span class="text-red-500">*</span></label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                               class="w-full border rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors border-gray-300"
                               placeholder="Ulangi password">
                    </div>
                </div>

                <button type="submit"
                        class="w-full bg-orange-600 text-white py-2.5 rounded-lg text-sm font-medium hover:bg-orange-700 transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 mt-2">
                    Daftar Sekarang
                </button>

                <p class="text-center text-sm text-gray-600">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-orange-600 font-medium hover:underline">Masuk di sini</a>
                </p>
            </form>
        </div>

        <p class="text-center text-orange-100 text-xs mt-6">&copy; {{ date('Y') }} POS System</p>
    </div>

</body>
</html>
