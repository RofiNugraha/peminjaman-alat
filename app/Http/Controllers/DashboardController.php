<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\LogAktivitas;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $role = Auth::user()->role;

        if ($role === 'admin') {
            return view('dashboard.index', [
                'totalUser' => User::count(),
                'totalAlat' => Alat::count(),
                'peminjamanAktif' => Peminjaman::where('status', 'disetujui')->count(),
                'totalLog' => LogAktivitas::count(),
            ]);
        }

        if ($role === 'petugas') {
            return view('dashboard.index', [
                'menunggu' => Peminjaman::where('status', 'menunggu')->count(),
                'disetujui' => Peminjaman::where('status', 'disetujui')->count(),
                'pengembalianHariIni' => Pengembalian::whereDate('tgl_dikembalikan', now())->count(),
            ]);
        }

        return view('dashboard.index', [
            'totalPinjam' => Peminjaman::where('id_user', Auth::id())->count(),
            'aktif' => Peminjaman::where('id_user', Auth::id())->where('status', 'disetujui')->count(),
            'selesai' => Peminjaman::where('id_user', Auth::id())->where('status', 'dikembalikan')->count(),
        ]);
    }
}