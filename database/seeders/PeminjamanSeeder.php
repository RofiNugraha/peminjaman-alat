<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Peminjaman;
use App\Models\PeminjamanItem;
use App\Models\User;
use App\Models\Alat;
use Illuminate\Support\Facades\DB;

class PeminjamanSeeder extends Seeder
{
    public function run(): void
    {
        $petugasList = User::where('role', 'petugas')->get();

        for ($i = 1; $i <= 120; $i++) {

            DB::transaction(function () use ($petugasList) {

                $user = User::where('role', 'peminjam')
                    ->whereHas('profilSiswa.dataSiswa')
                    ->inRandomOrder()
                    ->first();

                $alat = Alat::where('stok', '>', 0)->inRandomOrder()->first();

                if (!$user || !$alat) return;

                $rand = rand(1, 100);

                $status = match (true) {
                    $rand <= 50 => 'menunggu',
                    $rand <= 75 => 'disetujui',
                    $rand <= 90 => 'dikembalikan',
                    default => 'ditolak',
                };

                $tglPinjam  = now()->subDays(rand(0, 5));
                $tglKembali = now()->copy()->addDays(rand(1, 7));

                $qty = rand(1, min(2, $alat->stok));
                $petugas = $petugasList->random();

                $peminjaman = Peminjaman::create([
                    'kode_peminjaman' => 'PMJ-' . uniqid(),
                    'id_user'       => $user->id,
                    'tgl_pinjam'    => $tglPinjam,
                    'tgl_kembali'   => $tglKembali,
                    'status'        => $status,
                    'total_denda'   => 0,
                    'status_denda'  => 'tidak_ada',

                    'approved_by'   => in_array($status, ['disetujui', 'dikembalikan']) ? $petugas->id : null,
                    'approved_at'   => in_array($status, ['disetujui', 'dikembalikan']) ? now()->subDays(rand(0, 3)) : null,

                    'rejected_by'   => $status === 'ditolak' ? $petugas->id : null,
                    'rejected_at'   => $status === 'ditolak' ? now()->subDays(rand(0, 3)) : null,
                ]);

                PeminjamanItem::create([
                    'id_peminjaman' => $peminjaman->id,
                    'id_alat'       => $alat->id,
                    'qty'           => $qty,
                ]);

                if (in_array($status, ['disetujui', 'dikembalikan'])) {
                    $alat->decrement('stok', $qty);
                }

                if ($status === 'dikembalikan') {
                    $alat->increment('stok', $qty);
                }

                if ($status === 'dikembalikan' && rand(1, 100) <= 30) {

                    $hariTelat = rand(1, 5);
                    $denda = $hariTelat * 5000;

                    $peminjaman->update([
                        'total_denda'  => $denda,
                        'status_denda' => 'belum',
                    ]);
                }
            });
        }

        for ($i = 1; $i <= 5; $i++) {

            DB::transaction(function () use ($petugasList) {

                $user = User::where('role', 'peminjam')
                    ->whereHas('profilSiswa.dataSiswa')
                    ->inRandomOrder()
                    ->first();

                $alat = Alat::where('stok', '>', 0)->inRandomOrder()->first();

                if (!$user || !$alat) return;

                $qty = 1;
                $petugas = $petugasList->random();

                $tglPinjam  = now()->subDays(rand(7, 10));
                $tglKembali = now()->subDays(rand(1, 5));

                $peminjaman = Peminjaman::create([
                    'kode_peminjaman' => 'TELAT-' . uniqid(),
                    'id_user'       => $user->id,
                    'tgl_pinjam'    => $tglPinjam,
                    'tgl_kembali'   => $tglKembali,
                    'status'        => 'disetujui',
                    'total_denda'   => 0,
                    'status_denda'  => 'tidak_ada',

                    'approved_by'   => $petugas->id,
                    'approved_at'   => now()->subDays(5),
                ]);

                PeminjamanItem::create([
                    'id_peminjaman' => $peminjaman->id,
                    'id_alat'       => $alat->id,
                    'qty'           => $qty,
                ]);

                $alat->decrement('stok', $qty);
            });
        }

        $this->command->info('Seeder peminjaman siap (normal + telat)');
    }
}