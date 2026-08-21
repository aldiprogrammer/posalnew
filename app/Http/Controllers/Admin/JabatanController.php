<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function index()
    {
        $jabatans = Jabatan::latest()->paginate(10);

        return view('admin.jabatan.index', compact('jabatans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:jabatan,nama',
        ]);

        Jabatan::create($validated);

        return redirect()->route('admin.jabatan.index')->with('success', 'Jabatan berhasil ditambahkan.');
    }

    public function update(Request $request, Jabatan $jabatan)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:jabatan,nama,'.$jabatan->id,
        ]);

        $jabatan->update($validated);

        return redirect()->route('admin.jabatan.index')->with('success', 'Jabatan berhasil diperbarui.');
    }

    public function destroy(Jabatan $jabatan)
    {
        $jabatan->delete();

        return redirect()->route('admin.jabatan.index')->with('success', 'Jabatan berhasil dihapus.');
    }
}
