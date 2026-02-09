<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Peminjaman;
use App\Models\PeminjamanItem;
use App\Models\User;
use App\Models\Alat;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PeminjamanSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('role', 'peminjam')->first();
        $alats = Alat::all();

        if (!$user || $alats->isEmpty()) {
            $this->command->warn('User peminjam atau alat belum tersedia.');
            return;
        }

        $statuses = ['menunggu', 'disetujui', 'ditolak', 'dikembalikan', 'kadaluarsa'];

        for ($i = 1; $i <= 120; $i++) {

            $tglPinjam = Carbon::now()->subDays(rand(1, 180));
            $tglKembali = $tglPinjam->copy()->addDays(rand(1, 7));

            DB::transaction(function () use (
                $user,
                $alats,
                $statuses,
                $tglPinjam,
                $tglKembali
            ) {

                $peminjaman = Peminjaman::create([
                    'id_user'     => $user->id,
                    'tgl_pinjam'  => $tglPinjam->toDateString(),
                    'tgl_kembali' => $tglKembali->toDateString(),
                    'status'      => $statuses[array_rand($statuses)],
                    'created_at'  => $tglPinjam,
                    'updated_at'  => $tglPinjam,
                ]);

                $items = $alats->random(rand(1, 3));

                foreach ($items as $alat) {
                    PeminjamanItem::create([
                        'id_peminjaman' => $peminjaman->id,
                        'id_alat'       => $alat->id,
                        'qty'           => rand(1, min(3, $alat->stok ?: 1)),
                    ]);
                }
            });
        }

        $this->command->info('Seeder peminjaman + items berhasil dibuat (120 peminjaman).');
    }
}