<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index(): JsonResponse
    {
        $kategoris = Kategori::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar kategori',
            'data' => $kategoris,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:kategori,nama',
            'keterangan' => 'nullable|string',
        ]);

        $kategori = Kategori::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil ditambahkan.',
            'data' => $kategori,
        ], 201);
    }

    public function show(Kategori $kategori): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Detail kategori',
            'data' => $kategori,
        ]);
    }

    public function update(Request $request, Kategori $kategori): JsonResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:kategori,nama,'.$kategori->id,
            'keterangan' => 'nullable|string',
        ]);

        $kategori->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil diperbarui.',
            'data' => $kategori->fresh(),
        ]);
    }

    public function destroy(Kategori $kategori): JsonResponse
    {
        $kategori->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dihapus.',
        ]);
    }
}
