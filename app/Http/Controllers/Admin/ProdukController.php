<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    private const JENIS_MAKANAN = ['Restoran', 'Cafe'];

    private const SATUAN_LIST = [
        'Dus',
        'Kotak',
        'Renteng',
        'Bungkus',
        'Pcs',
        'Botol',
        'Lembar',
        'Lusin',
        'Ikat',
        'Role',
    ];

    private const SATUAN_FIELDS = [
        'satuan_besar',
        'isi',
        'qty',
        'harga_satuan_besar',
        'satuan_kecil',
        'qty_all',
        'harga_satuan_kecil',
    ];

    public function index()
    {
        $produks = Produk::with('kategori')->latest()->paginate(10);
        $modeSatuan = $this->modeSatuan();
        $satuanList = self::SATUAN_LIST;

        return view('admin.produk.index', compact('produks', 'modeSatuan', 'satuanList'));
    }

    public function store(Request $request)
    {
        $modeSatuan = $this->modeSatuan();

        $rules = $this->rules();
        $rules['harga'] = $modeSatuan ? 'nullable|numeric|min:0' : 'required|numeric|min:0';

        $validated = $request->validate($rules);

        if ($modeSatuan) {
            unset($validated['harga'], $validated['diskon']);
        } else {
            foreach (self::SATUAN_FIELDS as $field) {
                unset($validated[$field]);
            }
        }

        $validated = $this->normalizeSatuan($validated);

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
        $modeSatuan = $this->modeSatuan();

        $rules = $this->rules($produk->id);
        $rules['harga'] = $modeSatuan ? 'nullable|numeric|min:0' : 'required|numeric|min:0';

        $validated = $request->validate($rules);

        if ($modeSatuan) {
            unset($validated['harga'], $validated['diskon']);
        } else {
            foreach (self::SATUAN_FIELDS as $field) {
                unset($validated[$field]);
            }
        }

        $validated = $this->normalizeSatuan($validated);

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

    private function rules(?int $produkId = null): array
    {
        $uniqueKode = $produkId
            ? 'unique:produk,kode_produk,'.$produkId
            : 'unique:produk,kode_produk';

        return [
            'kode_produk' => 'nullable|string|max:50|'.$uniqueKode,
            'kategori_id' => 'required|exists:kategori,id',
            'nama' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'keterangan' => 'nullable|string',
            'diskon' => 'nullable|numeric|min:0',
            'stok' => 'required|in:tersedia,tidak tersedia',
            'satuan_besar' => 'nullable|string|max:100|in:'.implode(',', self::SATUAN_LIST),
            'isi' => 'nullable|integer|min:0',
            'qty' => 'nullable|integer|min:0',
            'harga_satuan_besar' => 'nullable|numeric|min:0',
            'satuan_kecil' => 'nullable|string|max:100|in:'.implode(',', self::SATUAN_LIST),
            'qty_all' => 'nullable|integer|min:0',
            'harga_satuan_kecil' => 'nullable|numeric|min:0',
        ];
    }

    private function modeSatuan(): bool
    {
        $jenisUsaha = auth()->user()->jenis_usaha ?? null;

        return ! in_array($jenisUsaha, self::JENIS_MAKANAN, true);
    }

    private function normalizeSatuan(array $validated): array
    {
        foreach (['isi', 'qty', 'harga_satuan_besar', 'qty_all', 'harga_satuan_kecil'] as $field) {
            if (! array_key_exists($field, $validated)) {
                continue;
            }

            $validated[$field] = $validated[$field] === '' ? null : (int) $validated[$field];
        }

        foreach (['satuan_besar', 'satuan_kecil'] as $field) {
            if (array_key_exists($field, $validated) && $validated[$field] === '') {
                $validated[$field] = null;
            }
        }

        return $validated;
    }
}
