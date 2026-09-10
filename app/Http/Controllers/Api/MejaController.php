<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Meja;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MejaController extends Controller
{
    public function index(): JsonResponse
    {
        $mejas = Meja::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar meja',
            'data' => $mejas,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'no_meja' => ['required', 'string', 'max:20', Rule::unique('meja', 'no_meja')->where('id_store', auth()->id())],
            'tanggal' => 'required|date',
        ]);

        $meja = Meja::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Meja berhasil ditambahkan.',
            'data' => $meja,
        ], 201);
    }

    public function show(Meja $meja): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Detail meja',
            'data' => $meja,
        ]);
    }

    public function update(Request $request, Meja $meja): JsonResponse
    {
        $validated = $request->validate([
            'no_meja' => ['required', 'string', 'max:20', Rule::unique('meja', 'no_meja')->ignore($meja->id)->where('id_store', auth()->id())],
            'tanggal' => 'required|date',
        ]);

        $meja->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Meja berhasil diperbarui.',
            'data' => $meja->fresh(),
        ]);
    }

    public function byStore(string $id_store): JsonResponse
    {
        $mejas = Meja::withoutGlobalScope('store')->where('id_store', $id_store)->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar meja untuk store '.$id_store,
            'data' => $mejas,
        ]);
    }

    public function destroy(Meja $meja): JsonResponse
    {
        $meja->delete();

        return response()->json([
            'success' => true,
            'message' => 'Meja berhasil dihapus.',
        ]);
    }
}
