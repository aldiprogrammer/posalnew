<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class PenggunaController extends Controller
{
    public function index()
    {
        $penggunas = Pengguna::with('jabatan')->latest()->paginate(10);
        $jabatans = Jabatan::orderBy('nama')->get();

        return view('admin.pengguna.index', compact('penggunas', 'jabatans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('pengguna', 'username')->where('id_store', auth()->id())],
            'jabatan_id' => 'required|exists:jabatan,id',
            'password' => ['required', Password::min(6)],
        ]);

        Pengguna::create($validated);

        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function update(Request $request, Pengguna $pengguna)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('pengguna', 'username')->ignore($pengguna->id)->where('id_store', auth()->id())],
            'jabatan_id' => 'required|exists:jabatan,id',
            'password' => ['nullable', Password::min(6)],
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $pengguna->update($validated);

        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(Pengguna $pengguna)
    {
        if ($pengguna->id === auth()->id()) {
            return redirect()->route('admin.pengguna.index')->with('error', 'Tidak dapat menghapus akun yang sedang login.');
        }

        $pengguna->delete();

        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
