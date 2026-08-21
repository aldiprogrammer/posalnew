<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\PotonganMember;
use Illuminate\Http\Request;

class PotonganMemberController extends Controller
{
    public function index(Request $request)
    {
        $memberId = $request->query('member_id');
        $member = $memberId ? Member::find($memberId) : null;

        $query = PotonganMember::with('member');
        if ($memberId) {
            $query->where('member_id', $memberId);
        }
        $potongans = $query->latest()->paginate(10);
        $members = Member::orderBy('nama')->get();

        return view('admin.potongan-member.index', compact('potongans', 'member', 'members'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis' => 'required|in:diskon,rupiah',
            'nominal' => 'required|numeric|min:0',
            'tanggal_mulai' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        PotonganMember::create($validated);

        return redirect()->route('admin.potongan-member.index', ['member_id' => $validated['member_id']])->with('success', 'Potongan member berhasil ditambahkan.');
    }

    public function update(Request $request, PotonganMember $potonganMember)
    {
        $validated = $request->validate([
            'jenis' => 'required|in:diskon,rupiah',
            'nominal' => 'required|numeric|min:0',
            'tanggal_mulai' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $potonganMember->update($validated);

        return redirect()->route('admin.potongan-member.index', ['member_id' => $potonganMember->member_id])->with('success', 'Potongan member berhasil diperbarui.');
    }

    public function destroy(PotonganMember $potonganMember)
    {
        $memberId = $potonganMember->member_id;
        $potonganMember->delete();

        return redirect()->route('admin.potongan-member.index', ['member_id' => $memberId])->with('success', 'Potongan member berhasil dihapus.');
    }
}
