<?php

namespace App\Services;

use App\Models\Peminjaman;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\BuktiPelunasanMail;

class DendaService
{
    public function tandaiLunas(Peminjaman $peminjaman): void
    {
        DB::transaction(function () use ($peminjaman) {

            if ($peminjaman->status_denda === 'lunas') {
                throw new \Exception('Denda sudah lunas.');
            }

            if ($peminjaman->total_denda <= 0) {
                throw new \Exception('Tidak ada denda.');
            }

            $peminjaman->update([
                'status_denda' => 'lunas'
            ]);

            Notification::create([
                'id_user' => $peminjaman->id_user,
                'judul'   => 'Denda Lunas',
                'pesan'   => 'Pembayaran denda telah diterima.',
                'notifiable_id'   => $peminjaman->id,
                'notifiable_type' => Peminjaman::class,
            ]);

            logAktivitas(
                'Mengubah',
                'Denda Peminjaman',
                "Melunasi denda (Kode {$peminjaman->kode_peminjaman}) sebesar Rp " .
                number_format($peminjaman->total_denda, 0, ',', '.')
            );
        });

        $peminjaman->load(['user','pengembalian.items.alat']);

        Mail::to($peminjaman->user->email)
            ->send(new BuktiPelunasanMail($peminjaman));
    }
}