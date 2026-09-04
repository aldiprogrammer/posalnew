<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profil;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function index(): JsonResponse
    {
        $profils = Profil::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar profil',
            'data' => $profils,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id_store' => 'nullable|string|max:255',
            'nama_usaha' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'nohp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('profil', 'public');
        }

        if (empty($validated['id_store'])) {
            $validated['id_store'] = $request->header('X-Store-Id') ?? (auth()->check() ? (string) auth()->id() : null);
        }

        $profil = Profil::withoutGlobalScope('store')->create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil ditambahkan.',
            'data' => $profil,
        ], 201);
    }

    public function show(Profil $profil): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Detail profil',
            'data' => $profil,
        ]);
    }

    public function update(Request $request, Profil $profil): JsonResponse
    {
        $validated = $request->validate([
            'nama_usaha' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'nohp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        if ($request->hasFile('logo')) {
            if ($profil->logo) {
                Storage::disk('public')->delete($profil->logo);
            }
            $validated['logo'] = $request->file('logo')->store('profil', 'public');
        }

        $profil->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui.',
            'data' => $profil->fresh(),
        ]);
    }

    public function destroy(Profil $profil): JsonResponse
    {
        if ($profil->logo) {
            Storage::disk('public')->delete($profil->logo);
        }
        $profil->delete();

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil dihapus.',
        ]);
    }

    public function byStore(string $id_store): JsonResponse
    {
        $profils = Profil::withoutGlobalScope('store')->where('id_store', $id_store)->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar profil untuk store '.$id_store,
            'data' => $profils,
        ]);
    }
}
