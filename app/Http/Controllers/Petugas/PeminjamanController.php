<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjamans = Peminjaman::with(['user', 'items.alat.kategori'])
            ->where('status', 'menunggu')
            ->orderBy('created_at', 'asc')
            ->paginate(10);

        return view('petugas.peminjaman.index', compact('peminjamans'));
    }

    public function approve(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'menunggu') {
            return back()->with('error', 'Pengajuan tidak dapat disetujui');
        }

        DB::beginTransaction();

        try {
            $items = $peminjaman->items()->with('alat')->lockForUpdate()->get();

            foreach ($items as $item) {
                if ($item->alat->stok < $item->qty) {
                    throw new \Exception(
                        "Stok {$item->alat->nama_alat} tidak mencukupi"
                    );
                }
            }

            foreach ($items as $item) {
                $item->alat->decrement('stok', $item->qty);
            }

            $peminjaman->update([
                'status' => 'disetujui'
            ]);

            DB::commit();

            return back()->with('success', 'Pengajuan berhasil disetujui & stok diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    public function reject(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'menunggu') {
            return back()->with('error', 'Pengajuan tidak dapat ditolak');
        }

        $peminjaman->update([
            'status' => 'ditolak',
        ]);

        return back()->with('success', 'Pengajuan berhasil ditolak');
    }
}