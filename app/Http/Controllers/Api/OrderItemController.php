<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderItemController extends Controller
{
    public function index(): JsonResponse
    {
        $items = Pesanan::with(['produk', 'kasir'])
            ->latest('tanggal')
            ->latest('id')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar order items',
            'data' => $items,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'kode_order' => ['required', 'string', Rule::exists('order', 'kode_order')],
            'produk_id' => 'required|exists:produk,id',
            'harga' => 'required|numeric|min:0',
            'qty' => 'required|integer|min:1',
            'diskon' => 'nullable|numeric|min:0',
            'tanggal' => 'required|date',
            'kasir_id' => 'required',
            'pembayaran' => 'nullable|in:tunai,transfer,qris',
        ]);

        $validated['diskon'] = $validated['diskon'] ?? 0;

        $item = Pesanan::create($validated);
        $item->load(['produk', 'kasir']);

        return response()->json([
            'success' => true,
            'message' => 'Order item berhasil ditambahkan.',
            'data' => $item,
        ], 201);
    }

    public function show(Pesanan $order_item): JsonResponse
    {
        $order_item->load(['produk', 'kasir']);

        return response()->json([
            'success' => true,
            'message' => 'Detail order item',
            'data' => $order_item,
        ]);
    }

    public function update(Request $request, Pesanan $order_item): JsonResponse
    {
        $validated = $request->validate([
            'kode_order' => ['required', 'string', Rule::exists('order', 'kode_order')],
            'produk_id' => 'required|exists:produk,id',
            'harga' => 'required|numeric|min:0',
            'qty' => 'required|integer|min:1',
            'diskon' => 'nullable|numeric|min:0',
            'tanggal' => 'required|date',
            'kasir_id' => 'required|exists:pengguna,id',
            'pembayaran' => 'nullable|in:tunai,transfer,qris',
        ]);

        $validated['diskon'] = $validated['diskon'] ?? 0;

        $order_item->update($validated);
        $order_item->load(['produk', 'kasir']);

        return response()->json([
            'success' => true,
            'message' => 'Order item berhasil diperbarui.',
            'data' => $order_item,
        ]);
    }

    public function destroy(Pesanan $order_item): JsonResponse
    {
        $order_item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Order item berhasil dihapus.',
        ]);
    }
}
