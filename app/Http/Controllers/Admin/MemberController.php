<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        $members = Member::latest()->paginate(10);

        return view('admin.member.index', compact('members'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|max:20|unique:member,nik',
            'alamat' => 'nullable|string',
            'tanggal_bergabung' => 'required|date',
        ]);

        Member::create($validated);

        return redirect()->route('admin.member.index')->with('success', 'Member berhasil ditambahkan.');
    }

    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|max:20|unique:member,nik,'.$member->id,
            'alamat' => 'nullable|string',
            'tanggal_bergabung' => 'required|date',
        ]);

        $member->update($validated);

        return redirect()->route('admin.member.index')->with('success', 'Member berhasil diperbarui.');
    }

    public function destroy(Member $member)
    {
        $member->delete();

        return redirect()->route('admin.member.index')->with('success', 'Member berhasil dihapus.');
    }
}
