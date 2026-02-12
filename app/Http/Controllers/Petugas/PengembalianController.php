<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Denda;
use App\Models\Notification;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PengembalianController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);
        if (!in_array($perPage, [5, 10, 25, 50, 100])) {
            $perPage = 10;
        }

        $search    = $request->search;
        $direction = $request->direction === 'asc' ? 'asc' : 'desc';
        $dateFrom  = $request->date_from;
        $dateTo    = $request->date_to;

        $peminjamans = Peminjaman::with(['user', 'items.alat'])
            ->where('status', 'disetujui')
            ->when($search, function ($q) use ($search) {
                $q->whereHas('user', fn($u) => 
                    $u->where('nama', 'like', "%{$search}%")
                )->orWhereHas('items.alat', fn($a) => 
                    $a->where('nama_alat', 'like', "%{$search}%")
                );
            })
            ->when($dateFrom && $dateTo, fn($q) =>
                $q->whereBetween('tgl_kembali', [$dateFrom, $dateTo])
            )
            ->when($dateFrom && !$dateTo, fn($q) =>
                $q->whereDate('tgl_kembali', '>=', $dateFrom)
            )
            ->when($dateTo && !$dateFrom, fn($q) =>
                $q->whereDate('tgl_kembali', '<=', $dateTo)
            )
            ->orderBy('tgl_kembali', $direction)
            ->paginate($perPage)
            ->withQueryString();

        if ($request->ajax()) {
            return view('petugas.pengembalian.partials.table', compact('peminjamans', 'perPage'))->render();
        }

        return view('petugas.pengembalian.index', compact('peminjamans', 'perPage'));
    }

    public function store($id)
    {
        $peminjaman = Peminjaman::with('items.alat')->lockForUpdate()->findOrFail($id);

        if ($peminjaman->status !== 'disetujui') {
            abort(403, 'Peminjaman tidak valid.');
        }

        DB::transaction(function () use ($peminjaman) {

            $tglHarusKembali = Carbon::parse($peminjaman->tgl_kembali);
            $tglDikembalikan = Carbon::today();

            $hariTelat = max(0, $tglHarusKembali->diffInDays($tglDikembalikan, false));

            $pengembalian = Pengembalian::create([
                'id_peminjaman'    => $peminjaman->id,
                'tgl_dikembalikan' => $tglDikembalikan,
                'hari_telat'       => $hariTelat,
            ]);

            foreach ($peminjaman->items as $item) {
                $item->alat->increment('stok', $item->qty);
            }

            if ($hariTelat > 0) {

                $totalDenda = 0;

                foreach ($peminjaman->items as $item) {
                    $totalDenda += $item->alat->denda_per_hari * $hariTelat;
                }

                Denda::create([
                    'id_pengembalian' => $pengembalian->id,
                    'tarif_per_hari'  => $totalDenda / $hariTelat,
                    'total_denda'     => $totalDenda,
                    'status'          => 'belum_ditindak',
                ]);

                $peminjaman->update([
                    'status'        => 'dikembalikan',
                    'total_denda'   => $totalDenda,
                    'status_denda'  => 'belum',
                ]);

                Notification::create([
                    'id_user' => $peminjaman->id_user,
                    'judul'   => 'Denda Keterlambatan',
                    'pesan'   => "Anda terlambat {$hariTelat} hari. Total denda: Rp " . number_format($totalDenda),
                ]);

            } else {

                $peminjaman->update([
                    'status'        => 'dikembalikan',
                    'total_denda'   => 0,
                    'status_denda'  => 'tidak_ada',
                ]);
            }
        });

        catat_log(Auth::user()->nama . ' memproses pengembalian oleh ' . $peminjaman->user->nama);

        return back()->with('success', 'Pengembalian berhasil diproses.');
    }
}