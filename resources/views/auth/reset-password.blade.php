<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Password Baru - POS Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-orange-500 via-orange-600 to-orange-700 font-sans antialiased flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="flex flex-col items-center">
            <img src="{{ asset('logo/logonew.png') }}" alt="Logo"
                 class="object-contain" style="height: 100px">
            <p class="text-orange-100 text-sm mb-4 mt-4">Buat password baru untuk akun Anda</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-800">Buat Password Baru</h2>
                <p class="text-sm text-gray-500">Pastikan password baru berbeda dari password lama Anda</p>
            </div>

            <form action="{{ route('password.update') }}" method="POST" class="px-8 py-6 space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $email) }}" required readonly
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm bg-gray-100 text-gray-600 outline-none cursor-not-allowed">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru <span class="text-red-500">*</span></label>
                    <input type="password" id="password" name="password" required autofocus
                           class="w-full border rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors @error('password') border-red-400 @else border-gray-300 @enderror"
                           placeholder="Minimal 8 karakter">
                    @error('password')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password <span class="text-red-500">*</span></label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors"
                           placeholder="Ulangi password baru">
                </div>

                <button type="submit"
                        class="w-full bg-orange-600 text-white py-2.5 rounded-lg text-sm font-medium hover:bg-orange-700 transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                    Simpan Password Baru
                </button>
            </form>
            <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 text-center">
                <p class="text-sm text-gray-600">
                    Link tidak valid atau sudah kedaluwarsa?
                    <a href="{{ route('password.request') }}" class="text-orange-600 font-medium hover:underline">Minta link baru</a>
                </p>
            </div>
        </div>

        <p class="text-center text-orange-100 text-xs mt-6">&copy; {{ date('Y') }} POSAL System</p>
    </div>

</body>
</html>
