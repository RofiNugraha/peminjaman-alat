<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DendaController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);
        if (!in_array($perPage, [5,10,25,50,100])) {
            $perPage = 10;
        }

        $search   = $request->search;
        $status   = $request->status_denda;
        $order    = $request->direction === 'asc' ? 'asc' : 'desc';

        $peminjamans = Peminjaman::with([
                'user',
                'items.alat',
                'pengembalian.denda'
            ])
            ->where('total_denda', '>', 0)
            ->when($status, fn ($q) =>
                $q->where('status_denda', $status)
            )
            ->when($search, function ($q) use ($search) {
                $q->whereHas('user', fn ($u) =>
                    $u->where('nama', 'like', "%{$search}%")
                )->orWhereHas('items.alat', fn ($a) =>
                    $a->where('nama_alat', 'like', "%{$search}%")
                );
            })
            ->orderBy('updated_at', $order)
            ->paginate($perPage)
            ->withQueryString();

        if ($request->ajax()) {
            return view('petugas.denda.partials.table', compact('peminjamans', 'perPage'))->render();
        }

        return view('petugas.denda.index', compact('peminjamans', 'perPage'));
    }

    public function ingatkan(Peminjaman $peminjaman)
    {
        if ($peminjaman->status_denda === 'lunas') {
            return back()->with('error', 'Denda sudah lunas.');
        }

        $denda = $peminjaman->pengembalian?->denda;

        if (!$denda) {
            return back()->with('error', 'Data denda tidak ditemukan.');
        }

        DB::transaction(function () use ($peminjaman, $denda) {
            Notification::create([
                'id_user' => $peminjaman->id_user,
                'judul'   => 'Pengingat Denda',
                'pesan'   => 'Anda memiliki denda sebesar Rp '
                            . number_format($peminjaman->total_denda)
                            . '. Segera lakukan pembayaran.',
            ]);

            $denda->update([
                'status' => 'diingatkan',
            ]);
        
            $peminjaman->update([
                'status_denda' => 'belum'
            ]);
        });

        catat_log(Auth::user()->nama . ' mengingatkan denda peminjaman ID ' . $peminjaman->id);

        return back()->with('success', 'Pengingat denda berhasil dikirim.');
    }

    public function lunas(Peminjaman $peminjaman)
    {
        if ($peminjaman->status_denda === 'lunas') {
            return back()->with('error', 'Denda sudah ditandai lunas.');
        }

        $denda = $peminjaman->pengembalian?->denda;

        if (!$denda) {
            return back()->with('error', 'Data denda tidak ditemukan.');
        }

        DB::transaction(function () use ($peminjaman, $denda) {
            $peminjaman->update([
                'status_denda' => 'lunas'
            ]);

            $denda->update([
                'status' => 'dibayar',
            ]);

            Notification::create([
                'id_user' => $peminjaman->id_user,
                'judul'   => 'Denda Lunas',
                'pesan'   => 'Terima kasih. Denda peminjaman Anda telah dinyatakan lunas.',
            ]);
        });

        catat_log(Auth::user()->nama . ' menandai denda lunas untuk peminjaman ID ' . $peminjaman->id);

        return back()->with('success', 'Denda berhasil ditandai sebagai lunas.');
    }
}