<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PotonganMember;
use Illuminate\Http\JsonResponse;

class PotonganMemberController extends Controller
{
    public function byStore(string $id_store): JsonResponse
    {
        $potongans = PotonganMember::withoutGlobalScope('store')
            ->where('id_store', $id_store)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar potongan member untuk store '.$id_store,
            'data' => $potongans,
        ]);
    }

    public function activeByStore(string $id_store): JsonResponse
    {
        $potongans = PotonganMember::withoutGlobalScope('store')
            ->where('id_store', $id_store)
            ->active()
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Potongan member aktif untuk store '.$id_store,
            'data' => $potongans,
        ]);
    }
}
