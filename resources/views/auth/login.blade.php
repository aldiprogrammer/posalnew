<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - POS Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-orange-500 via-orange-600 to-orange-700 font-sans antialiased flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="flex flex-col items-center mb-8">
            <div class="w-14 h-14 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center font-bold text-2xl text-white mb-3">P</div>
            <h1 class="text-2xl font-bold text-white tracking-wide">POS Admin</h1>
            <p class="text-orange-100 text-sm mt-1">Masuk untuk mengelola aplikasi</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-800">Login</h2>
                <p class="text-sm text-gray-500 mt-0.5">Silakan masuk dengan akun Anda</p>
            </div>

            <form action="{{ route('login.attempt') }}" method="POST" class="px-8 py-6 space-y-4">
                @csrf

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" required autofocus
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors"
                           placeholder="Masukkan username">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" id="password" name="password" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors"
                           placeholder="Masukkan password">
                </div>

                <button type="submit"
                        class="w-full bg-orange-600 text-white py-2.5 rounded-lg text-sm font-medium hover:bg-orange-700 transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                    Masuk
                </button>
            </form>

            <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 text-center">
                <p class="text-sm text-gray-600">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-orange-600 font-medium hover:underline">Daftar di sini</a>
                </p>
            </div>
        </div>

        <p class="text-center text-orange-100 text-xs mt-6">&copy; {{ date('Y') }} POS System</p>
    </div>

</body>
</html>
