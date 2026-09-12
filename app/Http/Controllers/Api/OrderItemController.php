<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\User;
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
            'id_store' => 'required|string|max:255',
            'kode_order' => ['required', 'string', Rule::exists('order', 'kode_order')],
            'produk_id' => 'required|exists:produk,id',
            'harga' => 'required|numeric|min:0',
            'qty' => 'required|integer|min:1',
            'diskon' => 'nullable|numeric|min:0',
            'tanggal' => 'required|date',
            'kasir_id' => 'nullable|string|max:255',
            'pembayaran' => 'nullable|string|max:255',
        ]);

        $validated['diskon'] = $validated['diskon'] ?? 0;

        $item = Pesanan::create($validated);

        $this->kurangiStok($item);

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
            'id_store' => 'required|string|max:255',
            'kode_order' => ['required', 'string', Rule::exists('order', 'kode_order')],
            'produk_id' => 'required|exists:produk,id',
            'harga' => 'required|numeric|min:0',
            'qty' => 'required|integer|min:1',
            'diskon' => 'nullable|numeric|min:0',
            'tanggal' => 'required|date',
            'kasir_id' => 'nullable|string|max:255',
            'pembayaran' => 'nullable|string|max:255',
        ]);

        $validated['diskon'] = $validated['diskon'] ?? 0;

        $user = User::find($order_item->id_store);
        $produk = null;
        if ($user && $user->jenis_usaha === 'Toko') {
            $produk = Produk::withoutGlobalScope('store')->find($order_item->produk_id);
        }

        $oldPcs = $produk
            ? $this->toPcs($produk, (int) $order_item->getOriginal('qty'), $order_item->getOriginal('harga'))
            : 0;

        $order_item->update($validated);

        if ($produk) {
            $newPcs = $this->toPcs($produk, $validated['qty'], $validated['harga']);
            $this->ubahStok($produk, $oldPcs - $newPcs);
        }

        $order_item->load(['produk', 'kasir']);

        return response()->json([
            'success' => true,
            'message' => 'Order item berhasil diperbarui.',
            'data' => $order_item,
        ]);
    }

    private function kurangiStok(Pesanan $item): void
    {
        $user = User::find($item->id_store);
        if (! $user || $user->jenis_usaha !== 'Toko') {
            return;
        }

        $produk = Produk::withoutGlobalScope('store')->find($item->produk_id);
        if (! $produk) {
            return;
        }

        $this->ubahStok($produk, -$this->toPcs($produk, $item->qty, $item->harga));
    }

    private function toPcs(Produk $produk, int $qty, mixed $harga): int
    {
        if (($produk->harga_satuan_besar ?? null) !== null && (int) $harga === (int) $produk->harga_satuan_besar) {
            $isi = $produk->isi !== null && $produk->isi > 0 ? $produk->isi : 1;

            return $qty * $isi;
        }

        return $qty;
    }

    private function ubahStok(Produk $produk, int $deltaPcs): void
    {
        if ($produk->qty_all === null) {
            return;
        }

        $produk->qty_all = max(0, $produk->qty_all + $deltaPcs);

        if ($produk->qty !== null) {
            $isi = $produk->isi !== null && $produk->isi > 0 ? $produk->isi : 1;
            $produk->qty = (int) floor($produk->qty_all / $isi);
        }

        $produk->save();
    }

    public function byStore(string $id_store): JsonResponse
    {
        $items = Pesanan::with(['produk', 'kasir'])
            ->withoutGlobalScope('store')
            ->where('order_items.id_store', $id_store)
            ->latest('tanggal')->latest('id')->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar order items untuk store '.$id_store,
            'data' => $items,
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
