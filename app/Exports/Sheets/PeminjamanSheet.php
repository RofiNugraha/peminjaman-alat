<?php

namespace App\Exports\Sheets;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Services\LaporanPeminjamanService;
use Maatwebsite\Excel\Concerns\WithTitle;

class PeminjamanSheet implements FromView, ShouldAutoSize, WithStyles, WithTitle
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function title(): string
    {
        return 'Peminjaman';
    }

    public function view(): View
    {
        $data = app(LaporanPeminjamanService::class)->getData($this->request);

        return view('petugas.laporan.excel.peminjaman', compact('data'));
    }

    public function styles(Worksheet $sheet)
    {
        $row = $sheet->getHighestRow();

        return [
            'A2:G2' => ['font' => ['bold' => true]],
            "A2:G{$row}" => [
                'borders' => ['allBorders' => ['borderStyle' => 'thin']]
            ],
        ];
    }
}