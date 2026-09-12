<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class StoreController extends Controller
{
    public function jenisUsaha(string $id_store): JsonResponse
    {
        $user = User::find($id_store);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Store tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Jenis usaha untuk store '.$id_store,
            'data' => [
                'id_store' => (string) $user->id,
                'nama_store' => $user->nama_store,
                'jenis_usaha' => $user->jenis_usaha,
            ],
        ]);
    }
}
