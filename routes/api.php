<?php

use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OrderItemController;
use App\Http\Controllers\Api\PenggunaController;
use App\Http\Controllers\Api\ProdukController;
use Illuminate\Support\Facades\Route;

Route::apiResource('kategori', KategoriController::class);
Route::apiResource('member', MemberController::class);
Route::apiResource('produk', ProdukController::class);
Route::apiResource('pengguna', PenggunaController::class);
Route::apiResource('order', OrderController::class);
Route::apiResource('order-items', OrderItemController::class);
