<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        $query = Pesanan::with(['produk', 'kasir']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_order', 'like', "%{$search}%")
                    ->orWhereHas('produk', fn ($q2) => $q2->where('nama', 'like', "%{$search}%"))
                    ->orWhereHas('kasir', fn ($q2) => $q2->where('nama', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        $pesanans = $query->latest('tanggal')->latest('id')->paginate(15)->withQueryString();

        return view('admin.pesanan.index', compact('pesanans'));
    }

    public function update(Request $request, Pesanan $pesanan)
    {
        $validated = $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'harga' => 'required|numeric|min:0',
            'qty' => 'required|integer|min:1',
            'diskon' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
            'kasir_id' => 'nullable|string|max:255',
            'pembayaran' => 'nullable|in:tunai,transfer,qris',
        ]);

        $pesanan->update($validated);

        return redirect()->route('admin.pesanan.index')->with('success', 'Pesanan berhasil diperbarui.');
    }

    public function destroy(Pesanan $pesanan)
    {
        $pesanan->delete();

        return redirect()->route('admin.pesanan.index')->with('success', 'Pesanan berhasil dihapus.');
    }
}
