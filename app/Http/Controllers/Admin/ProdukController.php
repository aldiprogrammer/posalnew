<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index()
    {
        $produks = Produk::with('kategori')->latest()->paginate(10);

        return view('admin.produk.index', compact('produks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_produk' => 'nullable|string|max:50|unique:produk,kode_produk',
            'kategori_id' => 'required|exists:kategori,id',
            'nama' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'keterangan' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'diskon' => 'nullable|numeric|min:0',
            'stok' => 'required|in:tersedia,tidak tersedia',
        ]);

        if (empty($validated['kode_produk'])) {
            unset($validated['kode_produk']);
        }

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('produk', 'public');
        }

        unset($validated['foto_temp']);
        Produk::create($validated);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, Produk $produk)
    {
        $validated = $request->validate([
            'kode_produk' => 'nullable|string|max:50|unique:produk,kode_produk,'.$produk->id,
            'kategori_id' => 'required|exists:kategori,id',
            'nama' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'keterangan' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'diskon' => 'nullable|numeric|min:0',
            'stok' => 'required|in:tersedia,tidak tersedia',
        ]);

        if (empty($validated['kode_produk'])) {
            unset($validated['kode_produk']);
        }

        if ($request->hasFile('foto')) {
            if ($produk->foto) {
                Storage::disk('public')->delete($produk->foto);
            }
            $validated['foto'] = $request->file('foto')->store('produk', 'public');
        } else {
            unset($validated['foto']);
        }

        $produk->update($validated);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Produk $produk)
    {
        if ($produk->foto) {
            Storage::disk('public')->delete($produk->foto);
        }

        $produk->delete();

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil dihapus.');
    }
}
