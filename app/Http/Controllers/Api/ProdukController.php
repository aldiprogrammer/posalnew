<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index(): JsonResponse
    {
        $produks = Produk::with('kategori')->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar produk',
            'data' => $produks,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'nama' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'keterangan' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'diskon' => 'nullable|numeric|min:0',
            'stok' => 'required|in:tersedia,tidak tersedia',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('produk', 'public');
        } else {
            unset($validated['foto']);
        }

        $produk = Produk::create($validated);
        $produk->load('kategori');

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan.',
            'data' => $produk,
        ], 201);
    }

    public function show(Produk $produk): JsonResponse
    {
        $produk->load('kategori');

        return response()->json([
            'success' => true,
            'message' => 'Detail produk',
            'data' => $produk,
        ]);
    }

    public function update(Request $request, Produk $produk): JsonResponse
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'nama' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'keterangan' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'diskon' => 'nullable|numeric|min:0',
            'stok' => 'required|in:tersedia,tidak tersedia',
        ]);

        if ($request->hasFile('foto')) {
            if ($produk->foto) {
                Storage::disk('public')->delete($produk->foto);
            }
            $validated['foto'] = $request->file('foto')->store('produk', 'public');
        } else {
            unset($validated['foto']);
        }

        $produk->update($validated);
        $produk->load('kategori');

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil diperbarui.',
            'data' => $produk,
        ]);
    }

    public function destroy(Produk $produk): JsonResponse
    {
        if ($produk->foto) {
            Storage::disk('public')->delete($produk->foto);
        }

        $produk->delete();

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dihapus.',
        ]);
    }
}
