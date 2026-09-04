<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\JabatanController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\LaporanOrderController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PegawaiController;
use App\Http\Controllers\Admin\PenggunaController;
use App\Http\Controllers\Admin\PesananController;
use App\Http\Controllers\Admin\PotonganMemberController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\Admin\ProfilController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('admin.dashboard')
        : redirect()->route('login');
});

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login.attempt');
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register'])->name('register.store');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::resource('pegawai', PegawaiController::class)->except(['show']);
    Route::resource('kategori', KategoriController::class)->except(['show', 'create', 'edit']);
    Route::resource('jabatan', JabatanController::class)->except(['show', 'create', 'edit']);
    Route::resource('pengguna', PenggunaController::class)->except(['show', 'create', 'edit']);
    Route::resource('produk', ProdukController::class)->except(['show', 'create', 'edit']);
    Route::resource('member', MemberController::class)->except(['show', 'create', 'edit']);
    Route::resource('potongan-member', PotonganMemberController::class)->except(['show', 'create', 'edit']);
    Route::resource('pesanan', PesananController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('order', OrderController::class)->only(['index', 'destroy']);
    Route::get('laporan-order', [LaporanOrderController::class, 'index'])->name('laporan-order.index');
    Route::get('profil', [ProfilController::class, 'edit'])->name('profil.edit');
    Route::put('profil', [ProfilController::class, 'update'])->name('profil.update');
});
