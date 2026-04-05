<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
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
            'kondisi.*' => 'required|in:baik,rusak,hilang',
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
                'id_peminjaman'    => $peminjaman->id,
                'id_petugas'       => Auth::id(),
                'tgl_dikembalikan'=> $today,
                'hari_telat'       => $hariTelat,
                'denda_telat'      => $dendaTelat,
            ]);

            $totalDendaBarang = 0;

            foreach ($peminjaman->items as $item) {

                $kondisi = $request->kondisi[$item->id];
                $qty = $item->qty;

                $denda = 0;

                if ($kondisi === 'rusak') {
                $denda = $this->parseRupiah($request->denda_rusak[$item->id] ?? 0);

                    if ($denda <= 0) {
                        throw new \Exception('Denda rusak tidak valid');
                    }
                }

                if ($kondisi === 'hilang') {
                    $denda = $this->parseRupiah($request->denda_hilang[$item->id] ?? 0);

                    if ($denda <= 0) {
                        throw new \Exception('Denda hilang tidak valid');
                    }
                }

                if (in_array($kondisi, ['rusak', 'hilang']) && $denda <= 0) {
                    throw new \Exception('Denda wajib diisi dan tidak boleh 0');
                }

                $totalDendaBarang += $denda * $qty;

                DB::table('pengembalian_items')->insert([
                    'id_pengembalian' => $pengembalian->id,
                    'id_alat' => $item->id_alat,
                    'qty' => $qty,
                    'kondisi' => $kondisi,
                    'denda' => $denda,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                if ($kondisi === 'baik') {
                    $item->alat->increment('stok', $qty);
                }
            }

            $totalDenda = $dendaTelat + $totalDendaBarang;

            if ($totalDenda > 0) {
                $statusDenda = 'belum';
            } else {
                $statusDenda = 'tidak_ada';
            }

            $peminjaman->update([
                'status' => 'dikembalikan',
                'total_denda' => $totalDenda,
                'status_denda' => $statusDenda,
            ]);
        });

        return redirect()
            ->route('petugas.pengembalian.index')
            ->with('success', 'Pengembalian berhasil diproses.');
    }
}