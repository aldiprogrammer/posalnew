<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PotonganMember;
use Illuminate\Http\Request;

class PotonganMemberController extends Controller
{
    public function index()
    {
        $potongans = PotonganMember::latest()->paginate(10);

        return view('admin.potongan-member.index', compact('potongans'));
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

        return redirect()->route('admin.potongan-member.index')->with('success', 'Potongan member berhasil ditambahkan.');
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

        return redirect()->route('admin.potongan-member.index')->with('success', 'Potongan member berhasil diperbarui.');
    }

    public function destroy(PotonganMember $potonganMember)
    {
        $potonganMember->delete();

        return redirect()->route('admin.potongan-member.index')->with('success', 'Potongan member berhasil dihapus.');
    }
}
