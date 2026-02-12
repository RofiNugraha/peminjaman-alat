<?php

namespace App\Exports;

use App\Models\Peminjaman;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PeminjamanExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct(array $filters)
    {
        $this->filters = [
            'from'   => $filters['from']   ?? null,
            'to'     => $filters['to']     ?? null,
            'status' => $filters['status'] ?? null,
        ];
    }

    public function collection()
    {
        $query = Peminjaman::with([
            'user',
            'pengembalian',
            'pengembalian.denda'
        ]);

        if (!empty($this->filters['from'])) {
            $query->whereDate('tgl_pinjam', '>=', $this->filters['from']);
        }

        if (!empty($this->filters['to'])) {
            $query->whereDate('tgl_pinjam', '<=', $this->filters['to']);
        }

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        return $query->get()->map(function ($p) {

            $tglKembaliReal = optional($p->pengembalian)->tgl_dikembalikan;
            $hariTelat      = optional($p->pengembalian)->hari_telat ?? 0;
            $statusDenda    = $p->status_denda;
            $totalDenda     = $p->total_denda ?? 0;

            return [
                'Nama Peminjam'      => $p->user->nama ?? '-',
                'Tanggal Pinjam'     => $p->tgl_pinjam,
                'Tanggal Kembali'    => $p->tgl_kembali,
                'Tanggal Dikembalikan' => $tglKembaliReal ?? '-',
                'Status Peminjaman'  => $p->status,
                'Hari Terlambat'     => $hariTelat,
                'Total Denda'        => $totalDenda,
                'Status Denda'       => $statusDenda,
                'Dibuat Pada'        => $p->created_at->format('Y-m-d'),
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
            'Status Peminjaman',
            'Hari Terlambat',
            'Total Denda',
            'Status Denda',
            'Tanggal Dicatat',
        ];
    }
}