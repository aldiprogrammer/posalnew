<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ppn;
use Illuminate\Http\Request;

class PpnController extends Controller
{
    public function index()
    {
        $ppns = Ppn::latest()->paginate(10);

        return view('admin.ppn.index', compact('ppns'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'persentase' => 'required|numeric|min:0|max:100',
        ]);
        $validated['aktif'] = $request->boolean('aktif');

        $ppn = Ppn::create($validated);

        if ($ppn->aktif) {
            $this->deactivateOthers($ppn);
        }

        return redirect()->route('admin.ppn.index')->with('success', 'PPN berhasil ditambahkan.');
    }

    public function update(Request $request, Ppn $ppn)
    {
        $validated = $request->validate([
            'persentase' => 'required|numeric|min:0|max:100',
        ]);
        $validated['aktif'] = $request->boolean('aktif');

        $ppn->update($validated);

        if ($ppn->aktif) {
            $this->deactivateOthers($ppn);
        }

        return redirect()->route('admin.ppn.index')->with('success', 'PPN berhasil diperbarui.');
    }

    public function toggleAktif(Ppn $ppn)
    {
        $ppn->aktif = ! $ppn->aktif;

        if ($ppn->aktif) {
            $this->deactivateOthers($ppn);
        }

        $ppn->save();

        $status = $ppn->aktif ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.ppn.index')->with('success', 'PPN berhasil '.$status.'.');
    }

    public function destroy(Ppn $ppn)
    {
        $ppn->delete();

        return redirect()->route('admin.ppn.index')->with('success', 'PPN berhasil dihapus.');
    }

    private function deactivateOthers(Ppn $ppn): void
    {
        Ppn::withoutGlobalScope('store')
            ->where('id_store', $ppn->id_store)
            ->where('id', '!=', $ppn->id)
            ->where('aktif', true)
            ->update(['aktif' => false]);
    }
}
