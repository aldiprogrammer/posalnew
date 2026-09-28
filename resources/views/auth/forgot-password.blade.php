<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - POS Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-orange-500 via-orange-600 to-orange-700 font-sans antialiased flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="flex flex-col items-center">
            <img src="{{ asset('logo/logonew.png') }}" alt="Logo"
                 class="object-contain" style="height: 100px">
            <p class="text-orange-100 text-sm mb-4 mt-4">Atur ulang password akun Anda</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-800">Lupa Password</h2>
                <p class="text-sm text-gray-500">Masukkan email yang terdaftar, kami akan mengirimkan link untuk membuat password baru</p>
            </div>

            <form action="{{ route('password.email') }}" method="POST" class="px-8 py-6 space-y-4">
                @csrf

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                        {{ $errors->first() }}
                    </div>
                @endif

                @if (session('status'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
                        {{ session('status') }}
                    </div>
                @endif

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors"
                           placeholder="Masukkan email terdaftar">
                </div>

                <button type="submit"
                        class="w-full bg-orange-600 text-white py-2.5 rounded-lg text-sm font-medium hover:bg-orange-700 transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                    Kirim Link Reset Password
                </button>
            </form>
            <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 text-center">
                <p class="text-sm text-gray-600">
                    Ingat passwordnya?
                    <a href="{{ route('login') }}" class="text-orange-600 font-medium hover:underline">Kembali ke login</a>
                </p>
            </div>
        </div>

        <p class="text-center text-orange-100 text-xs mt-6">&copy; {{ date('Y') }} POSAL System</p>
    </div>

</body>
</html>
