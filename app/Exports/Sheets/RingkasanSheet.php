<?php

namespace App\Exports\Sheets;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Services\LaporanPeminjamanService;
use Maatwebsite\Excel\Concerns\WithTitle;

class RingkasanSheet implements FromView, ShouldAutoSize, WithStyles, WithTitle
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function title(): string
    {
        return 'Ringkasan';
    }

    public function view(): View
    {
        $data = app(LaporanPeminjamanService::class)->getData($this->request);

        return view('petugas.laporan.excel.ringkasan', [
            'total' => $data->count(),
            'totalDenda' => $data->sum('total_denda'),
            'telat' => $data->filter(fn($d) => $d->is_telat)->count(),
            'status' => [
                'disetujui' => $data->where('status','disetujui')->count(),
                'dikembalikan' => $data->where('status','dikembalikan')->count(),
            ]
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}