<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string', 'min:4', 'max:50'],
            'password' => ['required', 'string', 'min:8', 'max:255'],
        ], [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'min' => [
                'string' => ':attribute minimal :min karakter.',
                'numeric' => ':attribute minimal bernilai :min.',
            ],
            'max' => [
                'string' => ':attribute maksimal :max karakter.',
                'numeric' => ':attribute maksimal bernilai :max.',
            ],
            'attributes' => [
                'username' => 'Username',
                'password' => 'Password',
            ],
        ]);

        if (Auth::attempt($credentials, false)) {
            $request->session()->regenerate();

            logAktivitas(
                'Login',
                'Autentikasi',
                "User '{$request->username}' berhasil login ke sistem"
            );

            return redirect()->route('dashboard')
                ->with('success', 'Login berhasil, selamat datang ' . Auth::user()->nama);
        }

        sleep(1);

        // ❌ LOG AKTIVITAS (LOGIN GAGAL)
        logAktivitas(
            'Login',
            'Autentikasi',
            "Percobaan login gagal untuk username '{$request->username}'"
        );

        return back()
            ->withErrors(['username' => 'Username atau password salah'])
            ->onlyInput('username');
    }

    public function logout(Request $request)
    {
        $namaUser = Auth::user()->nama ?? 'User';

        logAktivitas(
            'Logout',
            'Autentikasi',
            "User '{$namaUser}' keluar dari sistem"
        );

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}