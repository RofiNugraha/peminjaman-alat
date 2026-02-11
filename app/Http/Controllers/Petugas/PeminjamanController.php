<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);
        if (!in_array($perPage, [5,10,25,50,100])) {
            $perPage = 10;
        }

        $search     = $request->search;
        $direction  = $request->direction === 'asc' ? 'asc' : 'desc';
        $dateFrom   = $request->date_from;
        $dateTo     = $request->date_to;
        $status     = $request->status ?? 'menunggu';

        $peminjamans = Peminjaman::with(['user', 'items.alat.kategori'])
            ->when($search, function ($q) use ($search) {
                $q->whereHas('user', fn($u) => $u->where('nama', 'like', "%{$search}%"))
                ->orWhereHas('items.alat', fn($a) => $a->where('nama_alat', 'like', "%{$search}%"));
            })
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($dateFrom && $dateTo, fn($q) =>
                $q->whereBetween('tgl_pinjam', [$dateFrom, $dateTo])
            )
            ->when($dateFrom && !$dateTo, fn($q) =>
                $q->whereDate('tgl_pinjam', '>=', $dateFrom)
            )
            ->when($dateTo && !$dateFrom, fn($q) =>
                $q->whereDate('tgl_pinjam', '<=', $dateTo)
            )
            ->orderBy('created_at', $direction)
            ->paginate($perPage)
            ->withQueryString();

        if ($request->ajax()) {
            return view('petugas.peminjaman.partials.table', compact('peminjamans', 'perPage'))->render();
        }

        return view('petugas.peminjaman.index', compact('peminjamans', 'perPage'));
    }

    public function approve(Peminjaman $peminjaman)
    {
        DB::transaction(function () use ($peminjaman) {
            $peminjaman->lockForUpdate();

            if ($peminjaman->status !== 'menunggu') {
                abort(409, 'Pengajuan sudah diproses.');
            }

            $items = $peminjaman->items()->with('alat')->lockForUpdate()->get();

            foreach ($items as $item) {
                $alat = $item->alat;

                if ($alat->stok < $item->qty) {
                    throw new \Exception(
                        "Stok alat '{$alat->nama_alat}' tidak mencukupi."
                    );
                }
            }

            foreach ($items as $item) {
                $item->alat->decrement('stok', $item->qty);
            }

            $peminjaman->update([
                'status'        => 'disetujui',
                'total_denda'   => 0,
                'status_denda'  => 'tidak_ada',
            ]);
        });

        catat_log(Auth::user()->nama . ' menyetujui peminjaman oleh ' . $peminjaman->user->nama);

        return back()->with('success', 'Pengajuan peminjaman berhasil disetujui.');
    }

    public function reject(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'menunggu') {
            return back()->with('error', 'Pengajuan tidak dapat ditolak.');
        }

        $peminjaman->update([
            'status' => 'ditolak',
        ]);

        catat_log(Auth::user()->nama . ' menolak peminjaman oleh ' . $peminjaman->user->nama);

        return back()->with('success', 'Pengajuan berhasil ditolak.');
    }
}