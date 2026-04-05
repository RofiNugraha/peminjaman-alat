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

        $search = $request->search;
        $status = $request->status_denda;
        $order  = $request->direction === 'asc' ? 'asc' : 'desc';

        $peminjamans = Peminjaman::with([
                'user',
                'items.alat',
                'pengembalian.items.alat'
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
                )->orWhere('kode_peminjaman', 'like', "%{$search}%");
            })
            ->orderByRaw("CASE WHEN status_denda = 'belum' THEN 0 ELSE 1 END")
            ->orderBy('updated_at', $order)
            ->paginate($perPage)
            ->withQueryString();

        if ($request->ajax()) {
            return view('petugas.denda.partials.table', compact('peminjamans', 'perPage'))->render();
        }

        return view('petugas.denda.index', compact('peminjamans', 'perPage'));
    }

    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load([
            'user.profilSiswa.dataSiswa',
            'items.alat.kategoris',
            'pengembalian.items.alat'
        ]);

        return view('petugas.denda.show', compact('peminjaman'));
    }

    public function lunas(Peminjaman $peminjaman)
    {
        if ($peminjaman->status_denda === 'lunas') {
            return back()->with('error', 'Denda sudah lunas.');
        }

        if ($peminjaman->total_denda <= 0) {
            return back()->with('error', 'Tidak ada denda.');
        }

        DB::transaction(function () use ($peminjaman) {
            $peminjaman->update([
                'status_denda' => 'lunas'
            ]);

            Notification::create([
                'id_user' => $peminjaman->id_user,
                'judul'   => 'Denda Lunas',
                'pesan'   => 'Pembayaran denda telah diterima. Terima kasih.',
                'notifiable_id'   => $peminjaman->id,
                'notifiable_type' => Peminjaman::class,
            ]);
        });

        catat_log(Auth::user()->nama . ' melunasi denda (cash) peminjaman ID ' . $peminjaman->id);

        return back()->with('success', 'Denda berhasil dilunasi.');
    }

    public function ingatkan(Peminjaman $peminjaman)
    {
        if ($peminjaman->status_denda === 'lunas') {
            return back()->with('error', 'Denda sudah lunas.');
        }

        if ($peminjaman->total_denda <= 0) {
            return back()->with('error', 'Tidak ada denda.');
        }

        Notification::create([
            'id_user' => $peminjaman->id_user,
            'judul'   => 'Pengingat Denda',
            'pesan'   => 'Anda memiliki denda sebesar Rp '
                        . number_format($peminjaman->total_denda, 0, ',', '.')
                        . '. Segera lakukan pembayaran.',
            'notifiable_id'   => $peminjaman->id,
            'notifiable_type' => Peminjaman::class,
        ]);

        catat_log(Auth::user()->nama . ' mengirim pengingat denda ke peminjaman ID ' . $peminjaman->id);

        return back()->with('success', 'Pengingat berhasil dikirim.');
    }
}