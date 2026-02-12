<?php

namespace App\Exports;

use App\Models\Denda;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DendaExport implements FromCollection, WithHeadings
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
        $q = Denda::with('pengembalian.peminjaman.user');

        if (!empty($this->filters['from'])) {
            $q->whereDate('created_at', '>=', $this->filters['from']);
        }

        if (!empty($this->filters['to'])) {
            $q->whereDate('created_at', '<=', $this->filters['to']);
        }

        if (!empty($this->filters['status'])) {
            $q->where('status', $this->filters['status']);
        }

        return $q->get()->map(function ($d) {
            return [
                'Nama Peminjam'     => $d->pengembalian->peminjaman->user->nama,
                'Tanggal Kembali'  => $d->pengembalian->tgl_dikembalikan,
                'Total Denda'      => $d->total_denda,
                'Status Denda'     => $d->status,
                'Tanggal Dicatat'  => $d->created_at->format('Y-m-d'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama Peminjam',
            'Tanggal Kembali',
            'Total Denda',
            'Status Denda',
            'Tanggal Dicatat',
        ];
    }
}
