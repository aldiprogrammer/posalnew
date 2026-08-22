<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(): JsonResponse
    {
        $orders = Order::with(['member', 'items.produk'])
            ->withSum('items as jumlah_item', 'qty')
            ->latest('tanggal')
            ->latest('id')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar order',
            'data' => $orders,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'kode_order' => 'required|string|unique:order,kode_order',
            'total_harga' => 'required|numeric|min:0',
            'diskon' => 'nullable|numeric|min:0',
            'member_id' => 'nullable|exists:member,id',
            'meja' => 'required|string|max:255',
            'pembayaran' => 'nullable|string|max:255',
            'tanggal' => 'required|date',
            'status_cetak' => 'nullable|boolean',
        ]);

        $validated['diskon'] = $validated['diskon'] ?? 0;
        $validated['status_cetak'] = $validated['status_cetak'] ?? false;

        $order = Order::create($validated);
        $order->load(['member', 'items']);

        return response()->json([
            'success' => true,
            'message' => 'Order berhasil ditambahkan.',
            'data' => $order,
        ], 201);
    }

    public function show(Order $order): JsonResponse
    {
        $order->load(['member', 'items.produk', 'items.kasir']);

        return response()->json([
            'success' => true,
            'message' => 'Detail order',
            'data' => $order,
        ]);
    }

    public function update(Request $request, Order $order): JsonResponse
    {
        $validated = $request->validate([
            'kode_order' => 'required|string|unique:order,kode_order,'.$order->id,
            'total_harga' => 'required|numeric|min:0',
            'diskon' => 'nullable|numeric|min:0',
            'member_id' => 'nullable|exists:member,id',
            'meja' => 'required|string|max:255',
            'pembayaran' => 'nullable|string|max:255',
            'tanggal' => 'required|date',
            'status_cetak' => 'nullable|boolean',
        ]);

        $validated['diskon'] = $validated['diskon'] ?? 0;

        $order->update($validated);
        $order->load(['member', 'items.produk']);

        return response()->json([
            'success' => true,
            'message' => 'Order berhasil diperbarui.',
            'data' => $order,
        ]);
    }

    public function destroy(Order $order): JsonResponse
    {
        $order->delete();

        return response()->json([
            'success' => true,
            'message' => 'Order berhasil dihapus.',
        ]);
    }
}
