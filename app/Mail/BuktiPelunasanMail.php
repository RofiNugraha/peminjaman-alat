<?php

namespace App\Mail;

use App\Models\Peminjaman;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class BuktiPelunasanMail extends Mailable
{
    use Queueable, SerializesModels;

    public $peminjaman;

    public function __construct(Peminjaman $peminjaman)
    {
        $this->peminjaman = $peminjaman;
    }

    public function build()
    {
        $this->peminjaman = \App\Models\Peminjaman::with([
            'user',
            'pengembalian.items.alat'
        ])->find($this->peminjaman->id);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('emails.bukti_pelunasan', [
            'peminjaman' => $this->peminjaman
        ]);

        return $this->subject('Bukti Pelunasan Denda')
            ->view('emails.bukti_pelunasan')
            ->attachData(
                $pdf->output(),
                'bukti-pelunasan-'.$this->peminjaman->kode_peminjaman.'.pdf'
            );
    }
}