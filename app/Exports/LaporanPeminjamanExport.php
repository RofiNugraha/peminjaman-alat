<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LaporanPeminjamanExport implements FromView, WithMultipleSheets
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        return view('petugas.laporan.excel');
    }

    public function sheets(): array
    {
        return [
            new \App\Exports\Sheets\RingkasanSheet($this->request),
            new \App\Exports\Sheets\PeminjamanSheet($this->request),
            new \App\Exports\Sheets\DetailAlatSheet($this->request),
        ];
    }
}