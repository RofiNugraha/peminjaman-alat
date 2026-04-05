<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DendaController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);
        if (!in_array($perPage, [5,10,25,50,100])) {
            $perPage = 10;
        }

        $search = $request->search;
        $status = $request->status_denda;
        $order  = $request->direction === 'asc' ? 'asc' : 'desc';

        $peminjamans = Peminjaman::with([
                'user',
                'items.alat',
                'pengembalian.items.alat'
            ])
            ->where('id_user', Auth::id())
            ->where('total_denda', '>', 0)
            ->when($status, fn ($q) =>
                $q->where('status_denda', $status)
            )
            ->when($search, function ($q) use ($search) {
                $q->whereHas('items.alat', fn ($a) =>
                    $a->where('nama_alat', 'like', "%{$search}%")
                )->orWhere('kode_peminjaman', 'like', "%{$search}%");
            })
            ->orderByRaw("CASE WHEN status_denda = 'belum' THEN 0 ELSE 1 END")
            ->orderBy('updated_at', $order)
            ->paginate($perPage)
            ->withQueryString();

        if ($request->ajax()) {
            return view('peminjam.denda.partials.table', compact('peminjamans', 'perPage'))->render();
        }

        return view('peminjam.denda.index', compact('peminjamans', 'perPage'));
    }

    public function show(Peminjaman $peminjaman)
    {
        if ($peminjaman->id_user !== Auth::id()) {
            abort(403, 'Akses ditolak');
        }

        $peminjaman->load([
            'user.profilSiswa.dataSiswa',
            'items.alat.kategoris',
            'pengembalian.items.alat'
        ]);

        return view('peminjam.denda.show', compact('peminjaman'));
    }
}