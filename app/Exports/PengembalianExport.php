<?php

namespace App\Exports;

use App\Models\Pengembalian;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PengembalianExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct(array $filters)
    {
        $this->filters = [
            'from' => $filters['from'] ?? null,
            'to'   => $filters['to']   ?? null,
        ];
    }

    public function collection()
    {
        $query = Pengembalian::with(['peminjaman.user', 'denda']);

        if (!empty($this->filters['from'])) {
            $query->whereDate('tgl_dikembalikan', '>=', $this->filters['from']);
        }

        if (!empty($this->filters['to'])) {
            $query->whereDate('tgl_dikembalikan', '<=', $this->filters['to']);
        }

        return $query->get()->map(function ($k) {
            $peminjaman = $k->peminjaman;
            $user       = optional($peminjaman->user)->nama ?? '-';
            $denda      = optional($k->denda);
            $statusDenda = $denda ? $denda->status : 'tidak_ada';

            return [
                'Nama Peminjam'      => $user,
                'Tanggal Pinjam'     => $peminjaman->tgl_pinjam ?? '-',
                'Tanggal Kembali'    => $peminjaman->tgl_kembali ?? '-',
                'Tanggal Dikembalikan' => $k->tgl_dikembalikan,
                'Hari Terlambat'     => $k->hari_telat ?? 0,
                'Total Denda'        => $denda->total_denda ?? 0,
                'Status Denda'       => $statusDenda,
                'Tanggal Dicatat'    => $k->created_at->format('Y-m-d'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama Peminjam',
            'Tanggal Pinjam',
            'Tanggal Kembali',
            'Tanggal Dikembalikan',
            'Hari Terlambat',
            'Total Denda',
            'Status Denda',
            'Tanggal Dicatat',
        ];
    }
}