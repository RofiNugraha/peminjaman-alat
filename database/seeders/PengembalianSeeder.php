<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\PengembalianItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PengembalianSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            $peminjamans = Peminjaman::with('items.alat')->get();

            foreach ($peminjamans as $peminjaman) {

                if ($peminjaman->status !== 'disetujui') continue;

                $tglKembali = Carbon::parse($peminjaman->tgl_kembali);

                $tglDikembalikan = match (rand(1, 3)) {
                    1 => $tglKembali,
                    2 => $tglKembali->copy()->addDays(rand(1, 3)),
                    3 => $tglKembali->copy()->addDays(rand(2, 5)),
                };

                $hariTelat = max(0, $tglKembali->diffInDays($tglDikembalikan, false));

                $dendaTelat = 0;
                foreach ($peminjaman->items as $item) {
                    $dendaTelat += $item->alat->denda_per_hari * $hariTelat;
                }

                $pengembalian = Pengembalian::create([
                    'id_peminjaman'    => $peminjaman->id,
                    'id_petugas'       => 1,
                    'tgl_dikembalikan'=> $tglDikembalikan,
                    'hari_telat'       => $hariTelat,
                    'denda_telat'      => $dendaTelat,
                ]);

                $totalDendaBarang = 0;

                foreach ($peminjaman->items as $item) {

                    $qtyTotal = $item->qty;

                    $qtyRusak  = rand(0, $qtyTotal);
                    $sisa      = $qtyTotal - $qtyRusak;

                    $qtyHilang = rand(0, $sisa);
                    $qtyBaik   = $qtyTotal - $qtyRusak - $qtyHilang;

                    $dendaRusakPerUnit  = $qtyRusak > 0 ? rand(10000, 50000) : 0;
                    $dendaHilangPerUnit = $qtyHilang > 0 ? rand(50000, 150000) : 0;

                    $subtotalDenda =
                        ($qtyRusak * $dendaRusakPerUnit) +
                        ($qtyHilang * $dendaHilangPerUnit);

                    PengembalianItem::create([
                        'id_pengembalian' => $pengembalian->id,
                        'id_alat'         => $item->id_alat,
                        'qty_baik'        => $qtyBaik,
                        'qty_rusak'       => $qtyRusak,
                        'qty_hilang'      => $qtyHilang,
                        'denda'           => $subtotalDenda,
                    ]);

                    if ($qtyBaik > 0) {
                        $item->alat->increment('stok', $qtyBaik);
                    }

                    $totalDendaBarang += $subtotalDenda;
                }

                $totalDenda = $dendaTelat + $totalDendaBarang;

                $peminjaman->update([
                    'status'       => 'dikembalikan',
                    'total_denda'  => $totalDenda,
                    'status_denda' => $totalDenda > 0 ? 'belum' : 'tidak_ada'
                ]);
            }
        });
    }
}