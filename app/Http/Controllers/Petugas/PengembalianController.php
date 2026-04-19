<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

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
                )->orWhereHas('items.peminjaman', fn($a) => $a->where('kode_peminjaman', 'like', "%{$search}%"));
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

    public function show($id)
    {
        $peminjaman = Peminjaman::with([
            'user.profilSiswa.dataSiswa',
            'items.alat',
            'pengembalian.items.alat'
        ])->findOrFail($id);

        if ($peminjaman->status !== 'disetujui') {
            return redirect()
                ->route('petugas.pengembalian.index')
                ->with('error', 'Peminjaman tidak bisa diproses.');
        }

        if ($peminjaman->pengembalian) {
            return redirect()
                ->route('petugas.pengembalian.index')
                ->with('error', 'Peminjaman sudah dikembalikan.');
        }

        $today = Carbon::today();
        $tglKembali = Carbon::parse($peminjaman->tgl_kembali);

        $hariTelat = max(0, $tglKembali->diffInDays($today, false));

        $estimasiDendaTelat = 0;

        if ($hariTelat > 0) {
            foreach ($peminjaman->items as $item) {
                $estimasiDendaTelat += $item->alat->denda_per_hari * $hariTelat;
            }
        }

        return view('petugas.pengembalian.show', compact('peminjaman', 'hariTelat', 'estimasiDendaTelat'));
    }

    private function parseRupiah($value)
    {
        return (int) preg_replace('/[^0-9]/', '', $value);
    }

    public function store(Request $request, $id)
    {
        $peminjaman = Peminjaman::with('items.alat')
            ->lockForUpdate()
            ->findOrFail($id);

        if ($peminjaman->status !== 'disetujui') {
            abort(403, 'Peminjaman tidak valid.');
        }

        $request->validate([
            'qty_baik.*'   => 'nullable|integer|min:0',
            'qty_rusak.*'  => 'nullable|integer|min:0',
            'qty_hilang.*' => 'nullable|integer|min:0',
            'denda_rusak.*' => 'nullable',
            'denda_hilang.*' => 'nullable',
        ]);

        DB::transaction(function () use ($request, $peminjaman) {

            $today = Carbon::today();
            $tglKembali = Carbon::parse($peminjaman->tgl_kembali);

            $hariTelat = max(0, $tglKembali->diffInDays($today, false));

            $dendaTelat = 0;
            foreach ($peminjaman->items as $item) {
                $dendaTelat += $item->alat->denda_per_hari * $hariTelat;
            }

            $pengembalian = Pengembalian::create([
                'id_peminjaman'     => $peminjaman->id,
                'id_petugas'        => Auth::id(),
                'tgl_dikembalikan' => $today,
                'hari_telat'        => $hariTelat,
                'denda_telat'       => $dendaTelat,
            ]);

            $totalDendaBarang = 0;
            $ringkasanKondisi = [];

            foreach ($peminjaman->items as $item) {

                $qtyBaik   = (int) ($request->qty_baik[$item->id] ?? 0);
                $qtyRusak  = (int) ($request->qty_rusak[$item->id] ?? 0);
                $qtyHilang = (int) ($request->qty_hilang[$item->id] ?? 0);

                $totalQty = $qtyBaik + $qtyRusak + $qtyHilang;

                if ($totalQty !== $item->qty) {
                    throw new \Exception("Jumlah kondisi tidak sesuai dengan qty alat ({$item->alat->nama_alat})");
                }

                $dendaRusak  = $this->parseRupiah($request->denda_rusak[$item->id] ?? 0);
                $dendaHilang = $this->parseRupiah($request->denda_hilang[$item->id] ?? 0);

                if ($qtyRusak > 0 && $dendaRusak <= 0) {
                    throw new \Exception("Denda rusak harus diisi");
                }

                if ($qtyHilang > 0 && $dendaHilang <= 0) {
                    throw new \Exception("Denda hilang harus diisi");
                }

                $subtotalDenda = ($qtyRusak * $dendaRusak) + ($qtyHilang * $dendaHilang);

                $totalDendaBarang += $subtotalDenda;

                $ringkasanKondisi[] =
                    "{$item->alat->nama_alat} (Baik: {$qtyBaik}, Rusak: {$qtyRusak}, Hilang: {$qtyHilang})";

                DB::table('pengembalian_items')->insert([
                    'id_pengembalian' => $pengembalian->id,
                    'id_alat'         => $item->id_alat,
                    'qty_baik'        => $qtyBaik,
                    'qty_rusak'       => $qtyRusak,
                    'qty_hilang'      => $qtyHilang,
                    'denda'           => $subtotalDenda,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);

                if ($qtyBaik > 0) {
                    $item->alat->increment('stok', $qtyBaik);
                }
            }

            $totalDenda = $dendaTelat + $totalDendaBarang;

            $peminjaman->update([
                'status'       => 'dikembalikan',
                'total_denda'  => $totalDenda,
                'status_denda' => $totalDenda > 0 ? 'belum' : 'tidak_ada',
            ]);

            if ($totalDenda > 0) {
                Notification::create([
                    'id_user' => $peminjaman->id_user,
                    'judul'   => 'Denda Peminjaman',
                    'pesan'   => 'Pengembalian telah diproses. Anda memiliki denda sebesar Rp '
                                . number_format($totalDenda, 0, ',', '.')
                                . '. Silakan segera melakukan pembayaran.',
                    'notifiable_id'   => $peminjaman->id,
                    'notifiable_type' => Peminjaman::class,
                ]);
            } else {
                Notification::create([
                    'id_user' => $peminjaman->id_user,
                    'judul'   => 'Pengembalian Berhasil',
                    'pesan'   => 'Pengembalian telah diproses tanpa denda. Terima kasih.',
                    'notifiable_id'   => $peminjaman->id,
                    'notifiable_type' => Peminjaman::class,
                ]);
            }

            logAktivitas(
                'Mengubah',
                'Pengembalian',
                "Memproses pengembalian (Kode {$peminjaman->kode_peminjaman}) "
                . "(Telat {$hariTelat} hari, Total denda Rp " . number_format($totalDenda, 0, ',', '.') . ") "
                . "- Detail: " . implode(', ', $ringkasanKondisi)
            );
        });

        return redirect()
            ->route('petugas.pengembalian.index')
            ->with('success', 'Pengembalian berhasil diproses.');
    }
}