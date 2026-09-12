<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventaris;
use App\Models\Profil;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InventarisController extends Controller
{
    public function index()
    {
        $inventaris = Inventaris::latest()->paginate(10);
        $nextKodeBarang = $this->generateKodeBarang();

        return view('admin.inventaris.index', compact('inventaris', 'nextKodeBarang'));
    }

    public function exportLabel(Request $request)
    {
        $validated = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer'],
        ]);

        $inventaris = Inventaris::whereIn('id', $validated['ids'])->orderBy('kode_barang')->get();
        $profil = Profil::first();

        $pdf = Pdf::loadView('admin.inventaris.label-produk', compact('inventaris', 'profil'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('label-inventaris.pdf');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_barang' => ['required', 'string', 'max:255'],
            'kondisi_barang' => ['required', 'string', 'max:255'],
            'tgl_masuk' => ['required', 'date'],
        ]);

        $validated['kode_barang'] = $this->generateKodeBarang();

        Inventaris::create($validated);

        return redirect()->route('admin.inventaris.index')->with('success', 'Inventaris berhasil ditambahkan.');
    }

    public function update(Request $request, Inventaris $inventaris)
    {
        $validated = $request->validate([
            'nama_barang' => ['required', 'string', 'max:255'],
            'kondisi_barang' => ['required', 'string', 'max:255'],
            'tgl_masuk' => ['required', 'date'],
        ]);

        $inventaris->update($validated);

        return redirect()->route('admin.inventaris.index')->with('success', 'Inventaris berhasil diperbarui.');
    }

    public function destroy(Inventaris $inventaris)
    {
        $inventaris->delete();

        return redirect()->route('admin.inventaris.index')->with('success', 'Inventaris berhasil dihapus.');
    }

    private function generateKodeBarang(): string
    {
        $tertinggi = Inventaris::pluck('kode_barang')
            ->map(fn ($kode) => (int) substr($kode, 4))
            ->max() ?? 0;

        return 'BRG-'.str_pad((string) ($tertinggi + 1), 3, '0', STR_PAD_LEFT);
    }
}
