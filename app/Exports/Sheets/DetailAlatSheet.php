<?php

namespace App\Exports\Sheets;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Services\LaporanPeminjamanService;
use Maatwebsite\Excel\Concerns\WithTitle;

class DetailAlatSheet implements FromView, ShouldAutoSize, WithStyles, WithTitle
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function title(): string
    {
        return 'Detail Alat';
    }

    public function view(): View
    {
        $data = app(LaporanPeminjamanService::class)->getData($this->request);

        $rows = [];

        foreach ($data as $peminjaman) {
            foreach ($peminjaman->items as $item) {
                $rows[] = [
                    'kode' => $peminjaman->kode_peminjaman,
                    'nama' => $peminjaman->user->nama,
                    'alat' => $item->alat->nama_alat,
                    'qty'  => $item->qty,
                    'tgl'  => optional($peminjaman->tgl_pinjam)->format('d-m-Y'),
                ];
            }
        }

        return view('petugas.laporan.excel.detail_alat', compact('rows'));
    }

    public function styles(Worksheet $sheet)
    {
        $row = $sheet->getHighestRow();

        return [
            'A2:E2' => ['font' => ['bold' => true]],
            "A2:E{$row}" => [
                'borders' => ['allBorders' => ['borderStyle' => 'thin']]
            ],
        ];
    }
}