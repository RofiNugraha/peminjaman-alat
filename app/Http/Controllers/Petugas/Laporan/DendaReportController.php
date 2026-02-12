<?php

namespace App\Http\Controllers\Petugas\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Denda;
use Carbon\Carbon;
use App\Exports\DendaExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class DendaReportController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'from' => 'nullable|date',
            'to'   => 'nullable|date|after_or_equal:from',
            'status' => 'nullable|in:belum_ditindak,diingatkan,dibayar',
            'type' => 'nullable|in:harian,bulanan',
        ]);

        $from   = $request->from;
        $to     = $request->to;
        $status = $request->status;
        $type   = $request->type ?? 'harian';

        $dendas = Denda::with([
            'pengembalian.peminjaman.user'
        ])
            ->when(
                $status,
                fn($q) =>
                $q->where('status', $status)
            )
            ->when(
                $from && $to,
                fn($q) =>
                $q->whereBetween('created_at', [
                    Carbon::parse($from)->startOfDay(),
                    Carbon::parse($to)->endOfDay()
                ])
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($type === 'bulanan') {
            $rekap = Denda::selectRaw("
                    YEAR(created_at) as tahun,
                    MONTH(created_at) as bulan,
                    COUNT(*) as total_kasus,
                    SUM(total_denda) as total_denda
                ")
                ->when(
                    $status,
                    fn($q) =>
                    $q->where('status', $status)
                )
                ->when(
                    $from && $to,
                    fn($q) =>
                    $q->whereBetween('created_at', [
                        Carbon::parse($from)->startOfDay(),
                        Carbon::parse($to)->endOfDay()
                    ])
                )
                ->groupBy('tahun', 'bulan')
                ->orderBy('tahun', 'desc')
                ->orderBy('bulan', 'desc')
                ->get();
        } else {
            $rekap = Denda::selectRaw("
                    DATE(created_at) as tanggal,
                    COUNT(*) as total_kasus,
                    SUM(total_denda) as total_denda
                ")
                ->when(
                    $status,
                    fn($q) =>
                    $q->where('status', $status)
                )
                ->when(
                    $from && $to,
                    fn($q) =>
                    $q->whereBetween('created_at', [
                        Carbon::parse($from)->startOfDay(),
                        Carbon::parse($to)->endOfDay()
                    ])
                )
                ->groupBy('tanggal')
                ->orderBy('tanggal', 'desc')
                ->get();
        }

        return view('petugas.laporan.denda.index', compact(
            'dendas',
            'rekap',
            'type'
        ));
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(
            new DendaExport($request->only(['from', 'to', 'status'])),
            'laporan-denda.xlsx'
        );
    }

    public function exportPdf(Request $request)
    {
        $q = Denda::with('pengembalian.peminjaman.user');

        if ($request->from) {
            $q->whereDate('created_at', '>=', $request->from);
        }

        if ($request->to) {
            $q->whereDate('created_at', '<=', $request->to);
        }

        if ($request->status) {
            $q->where('status', $request->status);
        }

        $dendas = $q->get();

        $pdf = Pdf::loadView(
            'petugas.laporan.denda.pdf',
            [
                'dendas' => $dendas,
                'from' => $request->from,
                'to' => $request->to,
            ]
        );

        return $pdf->download('laporan-denda.pdf');
    }
}
