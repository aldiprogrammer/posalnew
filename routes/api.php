<?php

use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\MejaController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OrderItemController;
use App\Http\Controllers\Api\PenggunaController;
use App\Http\Controllers\Api\PotonganMemberController;
use App\Http\Controllers\Api\PpnController;
use App\Http\Controllers\Api\ProdukController;
use App\Http\Controllers\Api\ProfilController;
use Illuminate\Support\Facades\Route;

// Endpoint by store untuk mobile - sesuai request: GET api/produk/{id_store} return list per store
// byStore di path yang sama dengan show, jadi show dipindah ke detail/* agar tidak bentrok
Route::get('produk/store/{id_store}', [ProdukController::class, 'byStore']);
Route::get('produk/{id_store}', [ProdukController::class, 'byStore']);
Route::get('kategori/store/{id_store}', [KategoriController::class, 'byStore']);
Route::get('kategori/{id_store}', [KategoriController::class, 'byStore']);
Route::get('member/store/{id_store}', [MemberController::class, 'byStore']);
Route::get('member/{id_store}', [MemberController::class, 'byStore']);
Route::get('order/store/{id_store}', [OrderController::class, 'byStore']);
Route::get('order/{id_store}', [OrderController::class, 'byStore']);
Route::get('order-items/store/{id_store}', [OrderItemController::class, 'byStore']);
Route::get('order-items/{id_store}', [OrderItemController::class, 'byStore']);
Route::get('profil/store/{id_store}', [ProfilController::class, 'byStore']);
Route::get('profil/{id_store}', [ProfilController::class, 'byStore']);
Route::get('ppn/store/{id_store}', [PpnController::class, 'byStore']);
Route::get('ppn/{id_store}', [PpnController::class, 'byStore']);
Route::get('potongan-member/store/{id_store}', [PotonganMemberController::class, 'byStore']);
Route::get('potongan-member/{id_store}', [PotonganMemberController::class, 'byStore']);
Route::get('potongan-member/active/store/{id_store}', [PotonganMemberController::class, 'activeByStore']);
Route::get('potongan-member/active/{id_store}', [PotonganMemberController::class, 'activeByStore']);
Route::get('meja/store/{id_store}', [MejaController::class, 'byStore']);
Route::get('meja/{id_store}', [MejaController::class, 'byStore']);

Route::apiResource('kategori', KategoriController::class)->except(['show']);
Route::get('kategori/detail/{kategori}', [KategoriController::class, 'show']);
Route::apiResource('member', MemberController::class)->except(['show']);
Route::get('member/detail/{member}', [MemberController::class, 'show']);
Route::apiResource('produk', ProdukController::class)->except(['show']);
Route::get('produk/detail/{produk}', [ProdukController::class, 'show']);
Route::apiResource('ppn', PpnController::class)->except(['show']);
Route::get('ppn/detail/{ppn}', [PpnController::class, 'show']);
Route::apiResource('pengguna', PenggunaController::class);
Route::apiResource('order', OrderController::class)->except(['show']);
Route::get('order/detail/{order}', [OrderController::class, 'show']);
Route::apiResource('order-items', OrderItemController::class)->except(['show']);
Route::get('order-items/detail/{order_item}', [OrderItemController::class, 'show']);
Route::apiResource('profil', ProfilController::class)->except(['show']);
Route::get('profil/detail/{profil}', [ProfilController::class, 'show']);
Route::apiResource('meja', MejaController::class)->except(['show']);
Route::get('meja/detail/{meja}', [MejaController::class, 'show']);
