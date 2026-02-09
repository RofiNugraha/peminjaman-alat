<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Peminjaman;
use Carbon\Carbon;

class ExpirePeminjaman extends Command
{
    protected $signature = 'peminjaman:expire';
    protected $description = 'Update peminjaman menunggu menjadi kadaluarsa jika lewat tanggal pinjam';

    public function handle()
    {
        $today = Carbon::today();

        $count = Peminjaman::where('status', 'menunggu')
            ->whereDate('tgl_pinjam', '<', $today)
            ->update([
                'status' => 'kadaluarsa',
                'updated_at' => now(),
            ]);

        $this->info("{$count} peminjaman berhasil dikadaluarsakan.");

        return Command::SUCCESS;
    }
}