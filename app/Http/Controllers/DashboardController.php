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
        $user = Auth::user();
        $role = $user->role;
        $statistikBulanan = Peminjaman::selectRaw('MONTH(tgl_pinjam) as bulan, COUNT(*) as total')
            ->whereYear('tgl_pinjam', now()->year)
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        $bulanLabel = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

        $dataBulanan = [];
        for ($i = 1; $i <= 12; $i++) {
            $dataBulanan[] = $statistikBulanan[$i] ?? 0;
        }

        $statistikBulanan = Peminjaman::selectRaw('MONTH(tgl_pinjam) as bulan, COUNT(*) as total')
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        $aktivitasTerbaru = LogAktivitas::with('users')
            ->orderBy('waktu', 'desc')
            ->limit(5)
            ->get();

        $topAlat = DB::table('peminjaman_items')
            ->join('alats', 'alats.id', '=', 'peminjaman_items.id_alat')
            ->select('alats.nama_alat', DB::raw('SUM(qty) as total'))
            ->groupBy('alats.nama_alat')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        if ($role === 'admin') {

            return view('dashboard.index', [
                'totalUser' => User::count(),
                'totalAlat' => Alat::count(),
                'peminjamanAktif' => Peminjaman::where('status', 'disetujui')->count(),
                'totalLog' => LogAktivitas::count(),

                'totalDenda' => Peminjaman::sum('total_denda'),

                'statistikBulanan' => $statistikBulanan,
                'aktivitasTerbaru' => $aktivitasTerbaru,
                'bulanLabel' => $bulanLabel,
                'dataBulanan' => $dataBulanan,
                'topAlat' => $topAlat
            ]);
        }

        if ($role === 'petugas') {
            return view('dashboard.index', [
                'menunggu' => Peminjaman::where('status', 'menunggu')->count(),
                'disetujui' => Peminjaman::where('status', 'disetujui')->count(),
                'pengembalianHariIni' => Pengembalian::whereDate('tgl_dikembalikan', now())->count(),
                'bulanLabel' => $bulanLabel,
                'dataBulanan' => $dataBulanan,

                'aktivitasTerbaru' => $aktivitasTerbaru,
                'topAlat' => $topAlat
            ]);
        }

        return view('dashboard.index', [
            'totalPinjam' => Peminjaman::where('id_user', $user->id)->count(),
            'aktif' => Peminjaman::where('id_user', $user->id)->where('status', 'disetujui')->count(),
            'selesai' => Peminjaman::where('id_user', $user->id)->where('status', 'dikembalikan')->count(),
            'bulanLabel' => $bulanLabel,
            'dataBulanan' => $dataBulanan,

            'aktivitasTerbaru' => $aktivitasTerbaru
        ]);
    }
}