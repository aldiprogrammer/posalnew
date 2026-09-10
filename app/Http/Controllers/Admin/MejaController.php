<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meja;
use App\Models\Profil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;

class MejaController extends Controller
{
    public function index()
    {
        $mejas = Meja::latest()->paginate(10);

        return view('admin.meja.index', compact('mejas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_meja' => ['required', 'string', 'max:20', Rule::unique('meja', 'no_meja')->where('id_store', auth()->id())],
            'tanggal' => 'required|date',
        ]);

        Meja::create($validated);

        return redirect()->route('admin.meja.index')->with('success', 'Meja berhasil ditambahkan.');
    }

    public function update(Request $request, Meja $meja)
    {
        $validated = $request->validate([
            'no_meja' => ['required', 'string', 'max:20', Rule::unique('meja', 'no_meja')->ignore($meja->id)->where('id_store', auth()->id())],
            'tanggal' => 'required|date',
        ]);

        $meja->update($validated);

        return redirect()->route('admin.meja.index')->with('success', 'Meja berhasil diperbarui.');
    }

    public function destroy(Meja $meja)
    {
        $meja->delete();

        return redirect()->route('admin.meja.index')->with('success', 'Meja berhasil dihapus.');
    }

    public function exportPdf()
    {
        $mejas = Meja::orderBy('no_meja')->get();
        $profil = Profil::first();

        $pdf = Pdf::loadView('admin.meja.pdf-kartu', compact('mejas', 'profil'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('kartu-meja.pdf');
    }
}
