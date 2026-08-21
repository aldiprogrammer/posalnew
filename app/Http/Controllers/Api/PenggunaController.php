<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class PenggunaController extends Controller
{
    public function index(): JsonResponse
    {
        $penggunas = Pengguna::with('jabatan')->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar pengguna',
            'data' => $penggunas,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|alpha_dash|unique:pengguna,username',
            'jabatan_id' => 'required|exists:jabatan,id',
            'password' => ['required', Password::min(6)],
        ]);

        $pengguna = Pengguna::create($validated);
        $pengguna->load('jabatan');

        return response()->json([
            'success' => true,
            'message' => 'Pengguna berhasil ditambahkan.',
            'data' => $pengguna,
        ], 201);
    }

    public function show(Pengguna $pengguna): JsonResponse
    {
        $pengguna->load('jabatan');

        return response()->json([
            'success' => true,
            'message' => 'Detail pengguna',
            'data' => $pengguna,
        ]);
    }

    public function update(Request $request, Pengguna $pengguna): JsonResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|alpha_dash|unique:pengguna,username,'.$pengguna->id,
            'jabatan_id' => 'required|exists:jabatan,id',
            'password' => ['nullable', Password::min(6)],
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $pengguna->update($validated);
        $pengguna->load('jabatan');

        return response()->json([
            'success' => true,
            'message' => 'Pengguna berhasil diperbarui.',
            'data' => $pengguna,
        ]);
    }

    public function destroy(Pengguna $pengguna): JsonResponse
    {
        if ($pengguna->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus akun yang sedang login.',
            ], 403);
        }

        $pengguna->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pengguna berhasil dihapus.',
        ]);
    }
}
