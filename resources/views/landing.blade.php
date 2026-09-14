<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POSAL - Aplikasi Kasir Termudah</title>
    @fonts
    @vite(['resources/css/app.css'])
</head>
<body class="bg-white text-slate-800 font-sans antialiased scroll-smooth">

    {{-- Navbar --}}
    <nav class="fixed top-0 inset-x-0 z-50 bg-white/90 backdrop-blur border-b border-orange-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
            <a href="#home" class="flex items-center gap-2">
                <img src="{{ asset('logo/logo.png') }}" alt="POSAL" class="h-9 w-auto">
            </a>
            <div class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-600">
                <a href="#home" class="hover:text-orange-600 transition-colors">Beranda</a>
                <a href="#tentang" class="hover:text-orange-600 transition-colors">Tentang</a>
                <a href="#layanan" class="hover:text-orange-600 transition-colors">Layanan</a>
                <a href="#client" class="hover:text-orange-600 transition-colors">Client</a>
                <a href="#kontak" class="hover:text-orange-600 transition-colors">Kontak</a>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="hidden sm:inline-flex text-sm font-semibold text-slate-700 hover:text-orange-600 transition-colors">Masuk</a>
                <a href="{{ route('register') }}" class="inline-flex items-center px-5 py-2 rounded-lg bg-orange-600 text-white text-sm font-semibold hover:bg-orange-700 transition-colors">
                    Coba Gratis
                </a>
                <button id="mobile-menu-toggle" type="button" class="md:hidden w-10 h-10 rounded-lg text-slate-700 hover:bg-orange-50 flex items-center justify-center" aria-label="Menu">
                    <svg id="menu-icon-open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg id="menu-icon-close" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-orange-100 px-4 py-4 space-y-1">
            <a href="#home" class="nav-link-mobile block px-3 py-2.5 rounded-lg text-sm font-medium text-slate-700 hover:bg-orange-50 hover:text-orange-600 transition-colors">Beranda</a>
            <a href="#tentang" class="nav-link-mobile block px-3 py-2.5 rounded-lg text-sm font-medium text-slate-700 hover:bg-orange-50 hover:text-orange-600 transition-colors">Tentang</a>
            <a href="#layanan" class="nav-link-mobile block px-3 py-2.5 rounded-lg text-sm font-medium text-slate-700 hover:bg-orange-50 hover:text-orange-600 transition-colors">Layanan</a>
            <a href="#client" class="nav-link-mobile block px-3 py-2.5 rounded-lg text-sm font-medium text-slate-700 hover:bg-orange-50 hover:text-orange-600 transition-colors">Client</a>
            <a href="#kontak" class="nav-link-mobile block px-3 py-2.5 rounded-lg text-sm font-medium text-slate-700 hover:bg-orange-50 hover:text-orange-600 transition-colors">Kontak</a>
        </div>
    </nav>

    {{-- Jumbotron / Hero --}}
    <section id="home" class="relative overflow-hidden bg-gradient-to-br from-orange-50 via-white to-orange-100 pt-32 pb-20 lg:pt-40 lg:pb-28">
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-orange-200 rounded-full blur-3xl opacity-60"></div>
        <div class="absolute -bottom-32 -left-24 w-80 h-80 bg-amber-200 rounded-full blur-3xl opacity-50"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid lg:grid-cols-2 gap-12 items-center">
            <div class="reveal">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-orange-100 text-orange-700 text-sm font-semibold">
                    <span class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></span>
                    Aplikasi Kasir Termudah
                </span>
                <h1 class="mt-6 text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-slate-900 leading-tight">
                    Kelola Kasir Usaha Anda <span class="text-orange-600">Tanpa Ribet</span>
                </h1>
                <p class="mt-6 text-lg text-slate-600 max-w-xl">
                    POSAL membantu pencatatan penjualan, stok produk, member, hingga laporan keuangan dalam satu aplikasi yang sederhana dan cepat. Cocok untuk UMKM sampai restoran.
                </p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-7 py-3.5 rounded-xl bg-orange-600 text-white text-base font-semibold shadow-lg shadow-orange-600/30 hover:bg-orange-700 transition-all hover:-translate-y-0.5">
                        Mulai Sekarang
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                    <a href="#layanan" class="inline-flex items-center gap-2 px-7 py-3.5 rounded-xl bg-white text-slate-800 text-base font-semibold border border-orange-200 hover:border-orange-400 hover:text-orange-600 transition-colors">
                        Lihat Fitur
                    </a>
                </div>
                <div class="mt-10 flex items-center gap-8 text-sm text-slate-500">
                    <div>
                        <p class="text-2xl font-extrabold text-slate-900">5.000+</p>
                        <p>UMKM Terbantu</p>
                    </div>
                    <div class="w-px h-10 bg-orange-100"></div>
                    <div>
                        <p class="text-2xl font-extrabold text-slate-900">10.000+</p>
                        <p>Transaksi / Hari</p>
                    </div>
                    <div class="w-px h-10 bg-orange-100"></div>
                    <div>
                        <p class="text-2xl font-extrabold text-slate-900">4.9/5</p>
                        <p>Rating Pengguna</p>
                    </div>
                </div>
            </div>

            <div class="relative reveal" style="transition-delay: 150ms">
                <img src="{{ asset('logo/gambar.png') }}" alt="Tampilan Aplikasi Kasir POSAL"
                     class="w-full max-w-md mx-auto rounded-3xl shadow-2xl shadow-orange-200/60 border border-orange-100">
                <div class="absolute -bottom-5 -left-5 bg-orange-600 text-white px-5 py-3 rounded-2xl shadow-lg shadow-orange-600/40 rotate-[-3deg]">
                    <p class="text-sm font-semibold">&ldquo;Gampang banget pakainya!&rdquo;</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Tentang --}}
    <section id="tentang" class="py-20 lg:py-28">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl mx-auto text-center reveal">
                <span class="text-sm font-bold uppercase tracking-widest text-orange-600">Tentang Kami</span>
                <h2 class="mt-3 text-3xl lg:text-4xl font-extrabold tracking-tight text-slate-900">Kenapa Memilih POSAL?</h2>
                <p class="mt-4 text-slate-600">POSAL dirancang untuk siapa saja yang ingin mengelola usaha tanpa pusing mengurus administrasi yang rumit.</p>
            </div>

            <div class="mt-14 grid md:grid-cols-3 gap-8 reveal" style="transition-delay: 100ms">
                <div class="p-8 rounded-2xl border border-orange-100 bg-orange-50/50 hover:bg-orange-50 transition-colors">
                    <div class="w-12 h-12 rounded-xl bg-orange-600 text-white flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="mt-5 text-xl font-bold text-slate-900">Cepat &amp; Simpel</h3>
                    <p class="mt-3 text-slate-600">Proses transaksi hanya dalam hitungan detik dengan tampilan yang intuitif. Tidak perlu pelatihan berjam-jam.</p>
                </div>
                <div class="p-8 rounded-2xl border border-orange-100 bg-orange-50/50 hover:bg-orange-50 transition-colors">
                    <div class="w-12 h-12 rounded-xl bg-orange-600 text-white flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="mt-5 text-xl font-bold text-slate-900">Stok Akurat</h3>
                    <p class="mt-3 text-slate-600">Persediaan barang terpantau otomatis setiap transaksi. Hindari kehabisan stok maupun penumpukan barang.</p>
                </div>
                <div class="p-8 rounded-2xl border border-orange-100 bg-orange-50/50 hover:bg-orange-50 transition-colors">
                    <div class="w-12 h-12 rounded-xl bg-orange-600 text-white flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="mt-5 text-xl font-bold text-slate-900">Laporan Lengkap</h3>
                    <p class="mt-3 text-slate-600">Pantau pendapatan harian, bulanan, hingga tahunan. Data laporan siap ekspor ke PDF dan Excel.</p>
                </div>
            </div>

            <div class="mt-16 rounded-3xl bg-slate-900 text-white overflow-hidden">
                <div class="grid lg:grid-cols-2">
                    <div class="p-10 lg:p-14">
                        <span class="text-sm font-bold uppercase tracking-widest text-orange-400">Visi Kami</span>
                        <h3 class="mt-3 text-2xl lg:text-3xl font-extrabold">Aplikasi Kasir yang Membantu Usaha Kecil Tumbuh</h3>
                        <p class="mt-4 text-slate-400">Kami percaya setiap pemilik usaha berhak memiliki alat yang andal untuk berkembang. POSAL lahir dari kebutuhan nyata para pelaku UMKM akan kemudahan pencatatan dan penjualan.</p>
                        <a href="{{ route('register') }}" class="mt-6 inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-orange-500 text-white font-semibold hover:bg-orange-600 transition-colors">
                            Gabung Sekarang
                        </a>
                    </div>
                    <div class="relative bg-gradient-to-br from-orange-600 to-orange-800 p-10 lg:p-14 flex items-end">
                        <ul class="space-y-4 w-full">
                            <li class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-sm font-bold">1</span>
                                <span class="font-medium">Pencatatan kasir otomatis</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-sm font-bold">2</span>
                                <span class="font-medium">Manajemen produk &amp; stok</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-sm font-bold">3</span>
                                <span class="font-medium">Program member &amp; potongan harga</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-sm font-bold">4</span>
                                <span class="font-medium">Laporan keuangan terperinci</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Layanan / Service --}}
    <section id="layanan" class="py-20 lg:py-28 bg-gradient-to-b from-orange-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl mx-auto text-center reveal">
                <span class="text-sm font-bold uppercase tracking-widest text-orange-600">Layanan Kami</span>
                <h2 class="mt-3 text-3xl lg:text-4xl font-extrabold tracking-tight text-slate-900">Fitur Lengkap untuk Kebutuhan Kasir Anda</h2>
                <p class="mt-4 text-slate-600">Semua yang Anda butuhkan untuk mengelola penjualan, stok, dan pelanggan dalam satu dashboard.</p>
            </div>

            <div class="mt-14 grid sm:grid-cols-2 lg:grid-cols-3 gap-6 reveal" style="transition-delay: 100ms">
                <div class="p-8 rounded-2xl bg-white border border-orange-100 shadow-sm hover:shadow-lg hover:border-orange-300 transition-all">
                    <div class="w-12 h-12 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                    <h3 class="mt-5 text-lg font-bold text-slate-900">Point of Sale</h3>
                    <p class="mt-2 text-sm text-slate-600">Transaksi kasir yang cepat dengan tampilan tombol produk yang mudah dipahami karyawan.</p>
                </div>
                <div class="p-8 rounded-2xl bg-white border border-orange-100 shadow-sm hover:shadow-lg hover:border-orange-300 transition-all">
                    <div class="w-12 h-12 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <h3 class="mt-5 text-lg font-bold text-slate-900">Manajemen Produk</h3>
                    <p class="mt-2 text-sm text-slate-600">Kelola kategori, harga, dan stok produk dengan mudah serta cetak label produk otomatis.</p>
                </div>
                <div class="p-8 rounded-2xl bg-white border border-orange-100 shadow-sm hover:shadow-lg hover:border-orange-300 transition-all">
                    <div class="w-12 h-12 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h3 class="mt-5 text-lg font-bold text-slate-900">Member &amp; Potongan</h3>
                    <p class="mt-2 text-sm text-slate-600">Kelola program member, poin, dan potongan harga untuk meningkatkan loyalitas pelanggan.</p>
                </div>
                <div class="p-8 rounded-2xl bg-white border border-orange-100 shadow-sm hover:shadow-lg hover:border-orange-300 transition-all">
                    <div class="w-12 h-12 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h3 class="mt-5 text-lg font-bold text-slate-900">Laporan Transaksi</h3>
                    <p class="mt-2 text-sm text-slate-600">Laporan penjualan yang lengkap dan dapat diekspor ke PDF maupun Excel sesuai kebutuhan.</p>
                </div>
                <div class="p-8 rounded-2xl bg-white border border-orange-100 shadow-sm hover:shadow-lg hover:border-orange-300 transition-all">
                    <div class="w-12 h-12 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h6m-3-3v6m4 2h-8a3 3 0 01-3 3H6a2 2 0 01-2-2V5a2 2 0 012-2h2m8 11H7a2 2 0 00-2 2v3a2 2 0 002 2h10a2 2 0 002-2v-3a2 2 0 00-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="mt-5 text-lg font-bold text-slate-900">Pajak (PPN) Otomatis</h3>
                    <p class="mt-2 text-sm text-slate-600">Pengaturan PPN yang fleksibel dan perhitungan otomatis pada setiap transaksi penjualan.</p>
                </div>
                <div class="p-8 rounded-2xl bg-white border border-orange-100 shadow-sm hover:shadow-lg hover:border-orange-300 transition-all">
                    <div class="w-12 h-12 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7H21M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7M3 7l2-4h14l2 4M12 7v14m-4-8h8"/>
                        </svg>
                    </div>
                    <h3 class="mt-5 text-lg font-bold text-slate-900">Inventaris Usaha</h3>
                    <p class="mt-2 text-sm text-slate-600">Pencatatan inventaris dan aset usaha lengkap dengan cetak kartu label stok barang.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Client --}}
    <section id="client" class="py-20 lg:py-28">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl mx-auto text-center reveal">
                <span class="text-sm font-bold uppercase tracking-widest text-orange-600">Client Kami</span>
                <h2 class="mt-3 text-3xl lg:text-4xl font-extrabold tracking-tight text-slate-900">Dipercaya Ribuan Usaha di Indonesia</h2>
                <p class="mt-4 text-slate-600">Dari warung kopi hingga restoran, POSAL membantu pengusaha mengelola penjualan dengan mudah.</p>
            </div>

            <div class="mt-14 grid grid-cols-2 md:grid-cols-4 gap-6 reveal" style="transition-delay: 100ms">
                <div class="flex items-center justify-center h-20 rounded-xl bg-slate-50 border border-slate-100 text-slate-400 font-extrabold tracking-widest text-lg">WarungKu</div>
                <div class="flex items-center justify-center h-20 rounded-xl bg-slate-50 border border-slate-100 text-slate-400 font-extrabold tracking-widest text-lg">Kopi Senja</div>
                <div class="flex items-center justify-center h-20 rounded-xl bg-slate-50 border border-slate-100 text-slate-400 font-extrabold tracking-widest text-lg">Ayam Geprek 99</div>
                <div class="flex items-center justify-center h-20 rounded-xl bg-slate-50 border border-slate-100 text-slate-400 font-extrabold tracking-widest text-lg">Bakso Pakde</div>
                <div class="flex items-center justify-center h-20 rounded-xl bg-slate-50 border border-slate-100 text-slate-400 font-extrabold tracking-widest text-lg">Soto Lamongan</div>
                <div class="flex items-center justify-center h-20 rounded-xl bg-slate-50 border border-slate-100 text-slate-400 font-extrabold tracking-widest text-lg">Masakan Padang</div>
                <div class="flex items-center justify-center h-20 rounded-xl bg-slate-50 border border-slate-100 text-slate-400 font-extrabold tracking-widest text-lg">Minimarket 24</div>
                <div class="flex items-center justify-center h-20 rounded-xl bg-slate-50 border border-slate-100 text-slate-400 font-extrabold tracking-widest text-lg">Kedai Makan</div>
            </div>

            <div class="mt-14 grid md:grid-cols-3 gap-6 reveal" style="transition-delay: 100ms">
                <div class="p-8 rounded-2xl bg-orange-50/70 border border-orange-100">
                    <div class="flex text-orange-500 text-lg">â˜…â˜…â˜…â˜…â˜…</div>
                    <p class="mt-4 text-slate-700">&ldquo;Sejak pakai POSAL, antrian kasir jadi lebih cepat. Laporan harian juga langsung terlihat. Sangat direkomendasikan!&rdquo;</p>
                    <div class="mt-6 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-orange-600 text-white flex items-center justify-center font-bold">A</div>
                        <div>
                            <p class="text-sm font-bold text-slate-900">Andi Prasetyo</p>
                            <p class="text-xs text-slate-500">Pemilik WarungKu</p>
                        </div>
                    </div>
                </div>
                <div class="p-8 rounded-2xl bg-orange-50/70 border border-orange-100">
                    <div class="flex text-orange-500 text-lg">â˜…â˜…â˜…â˜…â˜…</div>
                    <p class="mt-4 text-slate-700">&ldquo;Fitur member dan potongan harganya sangat membantu restoran kami. Pelanggan jadi semakin loyal.&rdquo;</p>
                    <div class="mt-6 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-orange-600 text-white flex items-center justify-center font-bold">S</div>
                        <div>
                            <p class="text-sm font-bold text-slate-900">Sinta Dewi</p>
                            <p class="text-xs text-slate-500">Owner Kopi Senja</p>
                        </div>
                    </div>
                </div>
                <div class="p-8 rounded-2xl bg-orange-50/70 border border-orange-100">
                    <div class="flex text-orange-500 text-lg">â˜…â˜…â˜…â˜…â˜…</div>
                    <p class="mt-4 text-slate-700">&ldquo;Stok barang sekarang akurat, tidak pernah kehabisan lagi di jam sibuk. POSAL nomor satu!&rdquo;</p>
                    <div class="mt-6 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-orange-600 text-white flex items-center justify-center font-bold">B</div>
                        <div>
                            <p class="text-sm font-bold text-slate-900">Budi Santoso</p>
                            <p class="text-xs text-slate-500">Pemilik Bakso Pakde</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Kontak / Contact --}}
    <section id="kontak" class="py-20 lg:py-28 bg-gradient-to-b from-white to-orange-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl mx-auto text-center reveal">
                <span class="text-sm font-bold uppercase tracking-widest text-orange-600">Hubungi Kami</span>
                <h2 class="mt-3 text-3xl lg:text-4xl font-extrabold tracking-tight text-slate-900">Mari Berbicara dengan Kami</h2>
                <p class="mt-4 text-slate-600">Punya pertanyaan atau butuh demo gratis? Tim kami siap membantu Anda.</p>
            </div>

            <div class="mt-14 grid lg:grid-cols-5 gap-8 reveal" style="transition-delay: 100ms">
                <div class="lg:col-span-2 space-y-4">
                    <div class="p-6 rounded-2xl bg-white border border-orange-100 flex items-start gap-4">
                        <div class="w-11 h-11 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-slate-900">Alamat Kantor</p>
                            <p class="text-sm text-slate-600 mt-1">Komplek Azzahra-3 Stabat Baru, Stabat Kabupaten Langkat, Sumatera Utara, Indonesia</p>
                        </div>
                    </div>
                    <div class="p-6 rounded-2xl bg-white border border-orange-100 flex items-start gap-4">
                        <div class="w-11 h-11 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-slate-900">Telepon / WhatsApp</p>
                            <p class="text-sm text-slate-600 mt-1">+62 838-6726-2985</p>
                        </div>
                    </div>
                    <div class="p-6 rounded-2xl bg-white border border-orange-100 flex items-start gap-4">
                        <div class="w-11 h-11 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-slate-900">Email</p>
                            <p class="text-sm text-slate-600 mt-1">aldial171996@gmail.com</p>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-3 p-8 rounded-3xl bg-white border border-orange-100 shadow-sm">
                    <h3 class="text-xl font-bold text-slate-900">Kirim Pesan</h3>
                    <p class="mt-2 text-sm text-slate-600">Isi formulir di bawah, tim kami akan segera menghubungi Anda.</p>
                    <form class="mt-6 grid sm:grid-cols-2 gap-4">
                        <input type="text" placeholder="Nama Lengkap" required class="px-4 py-3 rounded-xl border border-slate-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition-all">
                        <input type="email" placeholder="Email" required class="px-4 py-3 rounded-xl border border-slate-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition-all">
                        <input type="text" placeholder="No. WhatsApp" class="px-4 py-3 rounded-xl border border-slate-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition-all sm:col-span-2">
                        <select class="px-4 py-3 rounded-xl border border-slate-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition-all sm:col-span-2 text-slate-600 bg-white">
                            <option value="">Pilih Topik</option>
                            <option value="demo">Pesan Demo Gratis</option>
                            <option value="pertanyaan">Pertanyaan Produk</option>
                            <option value="kerjasama">Kerja Sama</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                        <textarea rows="4" placeholder="Tulis pesan Anda..." required class="px-4 py-3 rounded-xl border border-slate-200 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition-all sm:col-span-2 resize-none"></textarea>
                        <button type="submit" class="sm:col-span-2 px-6 py-3.5 rounded-xl bg-orange-600 text-white font-semibold hover:bg-orange-700 transition-colors">
                            Kirim Pesan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Banner --}}
    <section class="pb-20 lg:pb-28">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-orange-600 to-orange-800 px-8 py-14 lg:px-16 lg:py-20 text-center text-white">
                <div class="absolute -top-20 -left-20 w-72 h-72 bg-orange-400 rounded-full blur-3xl opacity-40"></div>
                <div class="absolute -bottom-24 -right-16 w-72 h-72 bg-amber-300 rounded-full blur-3xl opacity-30"></div>
                <div class="relative">
                    <h2 class="text-3xl lg:text-4xl font-extrabold tracking-tight max-w-2xl mx-auto">Siap Mempermudah Usaha Anda dengan POSAL?</h2>
                    <p class="mt-4 text-orange-100 max-w-xl mx-auto">Daftar sekarang secara gratis dan rasakan kemudahan mengelola kasir, stok, dan laporan keuangan dalam satu aplikasi.</p>
                    <div class="mt-8 flex flex-wrap justify-center gap-4">
                        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-xl bg-white text-orange-600 font-semibold shadow-lg hover:bg-orange-50 transition-colors">
                            Daftar Gratis
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </a>
                        <a href="#kontak" class="inline-flex items-center px-8 py-3.5 rounded-xl bg-white/10 text-white font-semibold border border-white/30 hover:bg-white/20 transition-colors">
                            Hubungi Kami
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-slate-900 text-slate-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 grid md:grid-cols-4 gap-10">
            <div class="md:col-span-2">
                <a href="#home" class="flex items-center gap-2">
                    <div class="w-9 h-9 rounded-lg bg-orange-600 text-white flex items-center justify-center font-bold text-lg">P</div>
                    <span class="text-xl font-extrabold tracking-tight text-white">POSA<span class="text-orange-500">L</span></span>
                </a>
                <p class="mt-4 max-w-sm text-sm text-slate-400">Aplikasi kasir termudah untuk mengelola penjualan, stok, member, dan laporan keuangan usaha Anda.</p>
            </div>
            <div>
                <h4 class="text-sm font-bold uppercase tracking-widest text-white">Navigasi</h4>
                <ul class="mt-4 space-y-3 text-sm">
                    <li><a href="#home" class="hover:text-orange-400 transition-colors">Beranda</a></li>
                    <li><a href="#tentang" class="hover:text-orange-400 transition-colors">Tentang</a></li>
                    <li><a href="#layanan" class="hover:text-orange-400 transition-colors">Layanan</a></li>
                    <li><a href="#client" class="hover:text-orange-400 transition-colors">Client</a></li>
                    <li><a href="#kontak" class="hover:text-orange-400 transition-colors">Kontak</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-sm font-bold uppercase tracking-widest text-white">Ikuti Kami</h4>
                <ul class="mt-4 space-y-3 text-sm">
                    <li><a href="#" class="hover:text-orange-400 transition-colors">Instagram</a></li>
                    <li><a href="#" class="hover:text-orange-400 transition-colors">Facebook</a></li>
                    <li><a href="#" class="hover:text-orange-400 transition-colors">TikTok</a></li>
                    <li><a href="#" class="hover:text-orange-400 transition-colors">YouTube</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-slate-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-center text-sm text-slate-500">
                &copy; {{ date('Y') }} POSAL. Semua hak cipta dilindungi.
            </div>
        </div>
    </footer>

{{-- Scroll to Top --}}
    <button id="scroll-top" type="button" class="fixed bottom-6 right-6 z-40 w-11 h-11 rounded-full bg-orange-600 text-white shadow-lg shadow-orange-600/40 flex items-center justify-center opacity-0 pointer-events-none transition-all hover:bg-orange-700" aria-label="Kembali ke atas">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
        </svg>
    </button>

    <script>
        const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuIconOpen = document.getElementById('menu-icon-open');
        const menuIconClose = document.getElementById('menu-icon-close');

        mobileMenuToggle?.addEventListener('click', () => {
            const isHidden = mobileMenu.classList.toggle('hidden');
            menuIconOpen.classList.toggle('hidden', !isHidden);
            menuIconClose.classList.toggle('hidden', isHidden);
        });

        document.querySelectorAll('.nav-link-mobile').forEach((link) => {
            link.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
                menuIconOpen.classList.remove('hidden');
                menuIconClose.classList.add('hidden');
            });
        });

        const navLinks = document.querySelectorAll('nav a[href^="#"]');
        const sections = document.querySelectorAll('section[id]');

        const highlightNav = () => {
            let current = 'home';
            sections.forEach((section) => {
                if (window.scrollY >= section.offsetTop - 100) {
                    current = section.getAttribute('id');
                }
            });
            navLinks.forEach((link) => {
                const active = link.getAttribute('href') === `#${current}`;
                link.classList.toggle('text-orange-600', active);
                link.classList.toggle('font-bold', active);
            });
        };

        if ('IntersectionObserver' in window) {
            const revealObserver = new IntersectionObserver(
                (entries, observer) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('reveal-visible');
                            observer.unobserve(entry.target);
                        }
                    });
                },
                { threshold: 0.08 }
            );
            document.querySelectorAll('.reveal').forEach((el) => revealObserver.observe(el));
        } else {
            document.querySelectorAll('.reveal').forEach((el) => el.classList.add('reveal-visible'));
        }

        const scrollTop = document.getElementById('scroll-top');
        window.addEventListener('scroll', () => {
            highlightNav();
            const show = window.scrollY > 400;
            scrollTop.classList.toggle('opacity-0', !show);
            scrollTop.classList.toggle('pointer-events-none', !show);
        });
        scrollTop?.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
        highlightNav();
    </script>

</body>
</html>
