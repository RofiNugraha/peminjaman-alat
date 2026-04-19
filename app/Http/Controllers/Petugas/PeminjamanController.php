<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Alat;
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

        $peminjamans = Peminjaman::with(['user.profilSiswa.dataSiswa', 'items.alat.kategoris'])
            ->when($search, function ($q) use ($search) {
                $q->whereHas('user', fn($u) => $u->where('nama', 'like', "%{$search}%"))
                ->orWhereHas('items.alat', fn($a) => $a->where('nama_alat', 'like', "%{$search}%"))
                ->orWhereHas('items.peminjaman', fn($a) => $a->where('kode_peminjaman', 'like', "%{$search}%"));
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

    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load([
            'user.profilSiswa.dataSiswa',
            'items.alat.kategoris'
        ]);

        return view('petugas.peminjaman.show', compact('peminjaman'));
    }

    public function approve(Peminjaman $peminjaman)
    {
        DB::transaction(function () use ($peminjaman) {

            $peminjaman = Peminjaman::where('id', $peminjaman->id)
                ->lockForUpdate()
                ->first();

            if (!$peminjaman->canBeApproved()) {
                abort(409, 'Pengajuan sudah diproses.');
            }

            if ($peminjaman->isExpired()) {
                abort(422, 'Peminjaman sudah kadaluarsa.');
            }

            $items = $peminjaman->items()
                ->with('alat')
                ->lockForUpdate()
                ->get();

            foreach ($items as $item) {
                if ($item->alat->stok < $item->qty) {
                    throw new \Exception("Stok {$item->alat->nama_alat} tidak cukup");
                }
            }

            foreach ($items as $item) {
                Alat::where('id', $item->id_alat)
                    ->where('stok', '>=', $item->qty)
                    ->decrement('stok', $item->qty);
            }

            $peminjaman->update([
                'status'        => 'disetujui',
                'approved_by'   => Auth::id(),
                'approved_at'   => now(),
            ]);

            logAktivitas(
                'Mengubah',
                'Peminjaman',
                "Menyetujui pengajuan peminjaman (Kode {$peminjaman->kode_peminjaman})"
            );
        });

        return redirect()->route('petugas.peminjaman.index')
            ->with('success','Pengajuan berhasil disetujui.');
    }

    public function reject(Peminjaman $peminjaman)
    {
        if (!$peminjaman->canBeApproved()) {
            return back()->with('error', 'Pengajuan tidak dapat ditolak.');
        }

        if ($peminjaman->isExpired()) {
            return back()->with('error', 'Peminjaman sudah kadaluarsa.');
        }

        $peminjaman->update([
            'status'        => 'ditolak',
            'rejected_by'   => Auth::id(),
            'rejected_at'   => now(),
        ]);

        logAktivitas(
            'Mengubah',
            'Peminjaman',
            "Menolak pengajuan peminjaman (Kode {$peminjaman->kode_peminjaman})"
        );

        return redirect()->route('petugas.peminjaman.index')
            ->with('success','Pengajuan berhasil ditolak.');
    }
}