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

            $peminjamans = Peminjaman::with('items')->get();

            foreach ($peminjamans as $peminjaman) {
                if ($peminjaman->status !== 'disetujui') continue;

                $tglKembali = Carbon::parse($peminjaman->tgl_kembali);
                $scenario = rand(1, 5);

                $tglDikembalikan = match ($scenario) {
                    1 => $tglKembali,
                    2 => $tglKembali->copy()->addDays(rand(1, 3)),
                    3 => $tglKembali,
                    4 => $tglKembali,
                    5 => $tglKembali->copy()->addDays(rand(1, 5)),
                };

                $hariTelat = max(0, $tglKembali->diffInDays($tglDikembalikan, false));
                $dendaTelat = $hariTelat * 5000;

                $pengembalian = Pengembalian::create([
                    'id_peminjaman'   => $peminjaman->id,
                    'id_petugas'      => 1,
                    'tgl_dikembalikan'=> $tglDikembalikan,
                    'hari_telat'      => $hariTelat,
                    'denda_telat'     => $dendaTelat,
                ]);

                $totalDendaItem = 0;

                foreach ($peminjaman->items as $item) {

                    $kondisi = 'baik';
                    $denda = 0;

                    if ($scenario == 3) {
                        $kondisi = 'rusak';
                        $denda = 20000;
                    }

                    if ($scenario == 4) {
                        $kondisi = 'hilang';
                        $denda = 50000;
                    }

                    if ($scenario == 5) {
                        $random = rand(1, 3);
                        if ($random == 2) {
                            $kondisi = 'rusak';
                            $denda = 20000;
                        } elseif ($random == 3) {
                            $kondisi = 'hilang';
                            $denda = 50000;
                        }
                    }

                    PengembalianItem::create([
                        'id_pengembalian' => $pengembalian->id,
                        'id_alat'         => $item->id_alat,
                        'qty'             => $item->qty,
                        'kondisi'         => $kondisi,
                        'denda'           => $denda,
                    ]);

                    $totalDendaItem += $denda;
                }

                $totalDenda = $dendaTelat + $totalDendaItem;

                $peminjaman->update([
                    'status'       => 'dikembalikan',
                    'total_denda'  => $totalDenda,
                    'status_denda' => $totalDenda > 0 ? 'belum' : 'tidak_ada'
                ]);
            }
        });
    }
}