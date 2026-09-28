<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DownloadApkController;
use App\Http\Controllers\Admin\InventarisController;
use App\Http\Controllers\Admin\JabatanController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\LaporanOrderController;
use App\Http\Controllers\Admin\MejaController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PegawaiController;
use App\Http\Controllers\Admin\PenggunaController;
use App\Http\Controllers\Admin\PesananController;
use App\Http\Controllers\Admin\PotonganMemberController;
use App\Http\Controllers\Admin\PpnController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\Admin\ProfilController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'landing')->name('home');

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login.attempt');
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register'])->name('register.store');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('lupa-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
Route::post('lupa-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('throttle:6,1')
    ->name('password.email');
Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.update');

Route::middleware('auth')->group(function () {
    Route::get('email/verify', [VerificationController::class, 'notice'])->name('verification.notice');
    Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
        ->middleware('signed')
        ->name('verification.verify');
    Route::post('email/verification-notification', [VerificationController::class, 'send'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::resource('pegawai', PegawaiController::class)->except(['show']);
    Route::resource('kategori', KategoriController::class)->except(['show', 'create', 'edit']);
    Route::resource('jabatan', JabatanController::class)->except(['show', 'create', 'edit']);
    Route::resource('pengguna', PenggunaController::class)->except(['show', 'create', 'edit']);
    Route::resource('produk', ProdukController::class)->except(['show', 'create', 'edit']);
    Route::resource('meja', MejaController::class)->except(['show', 'create', 'edit']);
    Route::get('meja-export-pdf', [MejaController::class, 'exportPdf'])->name('meja.export-pdf');
    Route::resource('inventaris', InventarisController::class)->except(['show', 'create', 'edit']);
    Route::post('inventaris/export-label', [InventarisController::class, 'exportLabel'])->name('inventaris.export-label');
    Route::resource('member', MemberController::class)->except(['show', 'create', 'edit']);
    Route::resource('potongan-member', PotonganMemberController::class)->except(['show', 'create', 'edit']);
    Route::resource('ppn', PpnController::class)->except(['show', 'create', 'edit']);
    Route::post('ppn/{ppn}/toggle-aktif', [PpnController::class, 'toggleAktif'])->name('ppn.toggle-aktif');
    Route::resource('pesanan', PesananController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('order', OrderController::class)->only(['index', 'destroy']);
    Route::get('laporan-order', [LaporanOrderController::class, 'index'])->name('laporan-order.index');
    Route::get('laporan-order/export-pdf', [LaporanOrderController::class, 'exportPdf'])->name('laporan-order.export-pdf');
    Route::get('laporan-order/export-excel', [LaporanOrderController::class, 'exportExcel'])->name('laporan-order.export-excel');
    Route::get('profil', [ProfilController::class, 'edit'])->name('profil.edit');
    Route::put('profil', [ProfilController::class, 'update'])->name('profil.update');
    Route::get('download-apk', [DownloadApkController::class, 'index'])->name('download-apk.index');
    Route::get('download-apk/download', [DownloadApkController::class, 'download'])->name('download-apk.download');
});
