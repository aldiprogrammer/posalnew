<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - POS Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-orange-500 via-orange-600 to-orange-700 font-sans antialiased flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="flex flex-col items-center">
            <img src="{{ asset('logo/logonew.png') }}" alt="Logo"
                 class="object-contain" style="height: 100px">
            <p class="text-orange-100 text-sm mb-4 mt-4">Verifikasi akun Anda</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-800">Verifikasi Email</h2>
                <p class="text-sm text-gray-500">Terakhir di kirim: {{ auth()->user()->email }}</p>
            </div>

            <div class="px-8 py-6 space-y-4">
                @if (session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('status'))
                    <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg text-sm">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="bg-orange-50 border border-orange-200 text-orange-800 px-4 py-3 rounded-lg text-sm leading-relaxed">
                    Kami telah mengirimkan link verifikasi ke email <strong>{{ auth()->user()->email }}</strong>.
                    Silakan klik link tersebut untuk mengaktifkan akun Anda. Jika belum menerima email, klik tombol
                    di bawah untuk mengirim ulang.
                </div>

                <form action="{{ route('verification.send') }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="w-full bg-orange-600 text-white py-2.5 rounded-lg text-sm font-medium hover:bg-orange-700 transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                        Kirim Ulang Email Verifikasi
                    </button>
                </form>

                <form action="{{ route('logout') }}" method="POST" class="text-center">
                    @csrf
                    <button type="submit" class="text-sm text-gray-500 hover:text-red-600 transition-colors">
                        Keluar
                    </button>
                </form>
            </div>
        </div>

        <p class="text-center text-orange-100 text-xs mt-6">&copy; {{ date('Y') }} POSAL System</p>
    </div>

</body>
</html>