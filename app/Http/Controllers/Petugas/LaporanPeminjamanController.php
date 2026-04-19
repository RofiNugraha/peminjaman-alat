<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Alat;
use Illuminate\Http\Request;
use App\Services\LaporanPeminjamanService;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanPeminjamanController extends Controller
{
    public function index(Request $request, LaporanPeminjamanService $service)
    {
        $perPage = (int) $request->get('per_page', 10);
        if (!in_array($perPage, [5,10,25,50,100])) {
            $perPage = 10;
        }

        $dateType     = $request->get('date_type', 'tgl_pinjam');
        $startDate    = $request->start_date;
        $endDate      = $request->end_date;
        $status       = $request->status;
        $statusDenda  = $request->status_denda;
        $alatId       = $request->alat_id;

        $query = Peminjaman::with([
            'user',
            'items.alat',
            'pengembalian'
        ]);

        // FILTER TANGGAL DINAMIS
        $query->when($startDate && $endDate, function ($q) use ($dateType, $startDate, $endDate) {
            if ($dateType === 'tgl_dikembalikan') {
                $q->whereHas('pengembalian', function ($sub) use ($startDate, $endDate) {
                    $sub->whereBetween('tgl_dikembalikan', [$startDate, $endDate]);
                });
            } else {
                $q->whereBetween($dateType, [$startDate, $endDate]);
            }
        });

        // STATUS
        $query->when($status, fn($q) => $q->where('status', $status));

        // STATUS DENDA
        $query->when($statusDenda, fn($q) => $q->where('status_denda', $statusDenda));

        // FILTER ALAT
        $query->when($alatId, function ($q) use ($alatId) {
            $q->whereHas('items', function ($sub) use ($alatId) {
                $sub->where('id_alat', $alatId);
            });
        });

        $baseQuery = $service->query($request);
        $data = (clone $baseQuery)->latest()->paginate($perPage)->withQueryString();
        $summaryData = (clone $baseQuery)->get();
        $totalTransaksi = $summaryData->count();
        $totalDenda = $summaryData->sum('total_denda');
        $totalTelat = $summaryData->filter(fn($d) => $d->is_telat)->count();

        $alats = Alat::orderBy('nama_alat')->get();

        return view('petugas.laporan.index', compact('data', 'alats', 'perPage', 'totalTransaksi', 'totalDenda', 'totalTelat'));
    }

    public function exportPdf(Request $request, LaporanPeminjamanService $service)
    {
        $data = $service->getData($request);

        $pdf = Pdf::loadView('petugas.laporan.pdf', compact('data'))
        ->setPaper('A4', 'landscape');

        return $pdf->download('laporan-peminjaman.pdf');
    }

    public function exportExcel(Request $request)
    {
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\LaporanPeminjamanExport($request),
            'laporan-peminjaman.xlsx'
        );
    }

    public function getFilteredData(Request $request)
    {
        $dateType     = $request->get('date_type', 'tgl_pinjam');
        $startDate    = $request->start_date;
        $endDate      = $request->end_date;
        $status       = $request->status;
        $statusDenda  = $request->status_denda;
        $alatId       = $request->alat_id;

        $query = Peminjaman::with([
            'user',
            'items.alat',
            'pengembalian.items.alat'
        ]);

        $query->when($startDate && $endDate, function ($q) use ($dateType, $startDate, $endDate) {
            if ($dateType === 'tgl_dikembalikan') {
                $q->whereHas('pengembalian', function ($sub) use ($startDate, $endDate) {
                    $sub->whereBetween('tgl_dikembalikan', [$startDate, $endDate]);
                });
            } else {
                $q->whereBetween($dateType, [$startDate, $endDate]);
            }
        });

        $query->when($status, fn($q) => $q->where('status', $status));
        $query->when($statusDenda, fn($q) => $q->where('status_denda', $statusDenda));

        $query->when($alatId, function ($q) use ($alatId) {
            $q->whereHas('items', function ($sub) use ($alatId) {
                $sub->where('id_alat', $alatId);
            });
        });

        return $query->latest()->get();
    }
}