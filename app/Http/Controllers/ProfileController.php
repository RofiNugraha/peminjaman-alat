<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\ProfilSiswa;
use App\Models\DataSiswa;

class ProfileController extends Controller
{
    public function show()
    {
        $user = User::with('profilSiswa.dataSiswa')->findOrFail(Auth::id());

        return view('profile.show', compact('user'));
    }

    public function update(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        catat_log($user->nama . ' memperbarui profilnya.');

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'Password lama wajib diisi.',
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.min' => 'Password baru minimal 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
        }

        $user->update(['password' => Hash::make($request->new_password)]);
        catat_log($user->nama . ' memperbarui password akunnya.');
        Auth::logout();

        return redirect()->route('login')->with('success', 'Password berhasil diperbarui. Silakan login kembali.');
    }

    public function updateProfilSiswa(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'peminjam') {
            abort(403);
        }

        $validated = $request->validate([
            'nisn' => 'required|exists:data_siswas,nisn',
            'no_hp' => 'nullable|string|max:15',
            'no_hp_ortu' => 'nullable|string|max:15',
            'alamat' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'nisn.required' => 'NISN wajib diisi.',
            'nisn.exists' => 'NISN tidak ditemukan.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Format harus JPG/PNG.',
        ]);

        $dataSiswa = DataSiswa::where('nisn', $validated['nisn'])->first();

        $nisnDipakai = ProfilSiswa::where('nisn', $validated['nisn'])
        ->where('user_id', '!=', $user->id)
        ->exists();

        if ($nisnDipakai) {
            return back()->withErrors([
                'nisn' => 'NISN ini sudah digunakan oleh akun lain.'
            ])->withInput();
        }

        $fotoPath = optional($user->profilSiswa)->foto;

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('foto_siswa', 'public');
        }

        ProfilSiswa::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nisn' => $validated['nisn'],
                'no_hp' => $validated['no_hp'],
                'no_hp_ortu' => $validated['no_hp_ortu'],
                'alamat' => $validated['alamat'],
                'foto' => $fotoPath,
            ]
        );

        return back()->with('success', 'Profil siswa berhasil diperbarui.');
    }
}