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
            'username' => ['required', 'string', 'min:4', 'max:50',],
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
            catat_log(Auth::user()->nama . ' login ke sistem');
            return redirect()->route('dashboard');
        }

        sleep(1);

        return back()->withErrors(['username' => 'Username atau password salah',])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        catat_log(Auth::user()->nama . ' logout dari sistem');
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}