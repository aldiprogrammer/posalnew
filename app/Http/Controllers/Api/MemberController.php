<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(): JsonResponse
    {
        $members = Member::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar member',
            'data' => $members,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|max:20|unique:member,nik',
            'alamat' => 'nullable|string',
            'tanggal_bergabung' => 'required|date',
        ]);

        $member = Member::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Member berhasil ditambahkan.',
            'data' => $member,
        ], 201);
    }

    public function show(Member $member): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Detail member',
            'data' => $member,
        ]);
    }

    public function update(Request $request, Member $member): JsonResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|max:20|unique:member,nik,'.$member->id,
            'alamat' => 'nullable|string',
            'tanggal_bergabung' => 'required|date',
        ]);

        $member->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Member berhasil diperbarui.',
            'data' => $member->fresh(),
        ]);
    }

    public function destroy(Member $member): JsonResponse
    {
        $member->delete();

        return response()->json([
            'success' => true,
            'message' => 'Member berhasil dihapus.',
        ]);
    }
}
