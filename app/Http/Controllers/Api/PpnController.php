<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ppn;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PpnController extends Controller
{
    public function index(): JsonResponse
    {
        $ppns = Ppn::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar PPN',
            'data' => $ppns,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'persentase' => 'required|numeric|min:0|max:100',
            'aktif' => 'nullable|boolean',
        ]);
        $validated['aktif'] = $request->boolean('aktif');

        $ppn = Ppn::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'PPN berhasil ditambahkan.',
            'data' => $ppn,
        ], 201);
    }

    public function show(Ppn $ppn): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Detail PPN',
            'data' => $ppn,
        ]);
    }

    public function update(Request $request, Ppn $ppn): JsonResponse
    {
        $validated = $request->validate([
            'persentase' => 'required|numeric|min:0|max:100',
            'aktif' => 'nullable|boolean',
        ]);
        $validated['aktif'] = $request->boolean('aktif');

        $ppn->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'PPN berhasil diperbarui.',
            'data' => $ppn->fresh(),
        ]);
    }

    /**
     * GET api/ppn/store/{id_store} - untuk mobile: ambil PPN per store.
     */
    public function byStore(string $id_store): JsonResponse
    {
        $ppns = Ppn::withoutGlobalScope('store')
            ->where('id_store', $id_store)
            ->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar PPN untuk store '.$id_store,
            'data' => $ppns,
        ]);
    }

    public function destroy(Ppn $ppn): JsonResponse
    {
        $ppn->delete();

        return response()->json([
            'success' => true,
            'message' => 'PPN berhasil dihapus.',
        ]);
    }
}
