<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profil;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function edit(): View
    {
        $profil = Profil::firstOrNew();

        return view('admin.profil.index', compact('profil'));
    }

    public function update(Request $request)
    {
        $profil = Profil::firstOrNew();

        $validated = $request->validate([
            'nama_usaha' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'nohp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        if ($request->hasFile('logo')) {
            if ($profil->logo) {
                Storage::disk('public')->delete($profil->logo);
            }
            $validated['logo'] = $request->file('logo')->store('profil', 'public');
        }

        $profil->fill($validated)->save();

        return redirect()->route('admin.profil.edit')->with('success', 'Profil usaha berhasil diperbarui.');
    }
}
