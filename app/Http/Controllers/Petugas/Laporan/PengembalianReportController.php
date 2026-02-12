<?php

namespace App\Http\Controllers\Petugas\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengembalian;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PengembalianExport;

class PengembalianReportController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'from' => 'nullable|date',
            'to'   => 'nullable|date|after_or_equal:from',
            'type' => 'nullable|in:harian,bulanan',
        ]);

        $from = $request->from;
        $to   = $request->to;
        $type = $request->type ?? 'harian';

        $pengembalians = Pengembalian::with(['peminjaman.user', 'denda'])
            ->when(
                $from && $to,
                fn($q) =>
                $q->whereBetween('tgl_dikembalikan', [
                    Carbon::parse($from)->startOfDay(),
                    Carbon::parse($to)->endOfDay()
                ])
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($type === 'bulanan') {
            $rekap = Pengembalian::selectRaw("
                    YEAR(tgl_dikembalikan) as tahun,
                    MONTH(tgl_dikembalikan) as bulan,
                    COUNT(*) as total_pengembalian
                ")
                ->when(
                    $from && $to,
                    fn($q) =>
                    $q->whereBetween('tgl_dikembalikan', [
                        Carbon::parse($from)->startOfDay(),
                        Carbon::parse($to)->endOfDay()
                    ])
                )
                ->groupBy('tahun', 'bulan')
                ->orderBy('tahun', 'desc')
                ->orderBy('bulan', 'desc')
                ->paginate(10)
                ->withQueryString();
        } else {
            $rekap = Pengembalian::selectRaw("
                    DATE(tgl_dikembalikan) as tanggal,
                    COUNT(*) as total_pengembalian
                ")
                ->when(
                    $from && $to,
                    fn($q) =>
                    $q->whereBetween('tgl_dikembalikan', [
                        Carbon::parse($from)->startOfDay(),
                        Carbon::parse($to)->endOfDay()
                    ])
                )
                ->groupBy('tanggal')
                ->orderBy('tanggal', 'desc')
                ->paginate(10)
                ->withQueryString();
        }

        return view('petugas.laporan.pengembalian.index', compact(
            'pengembalians',
            'rekap',
            'type'
        ));
    }

    public function exportPdf(Request $request)
    {
        $q = Pengembalian::with(['peminjaman.user', 'denda']);

        if ($request->from) {
            $q->whereDate('tgl_dikembalikan', '>=', $request->from);
        }

        if ($request->to) {
            $q->whereDate('tgl_dikembalikan', '<=', $request->to);
        }

        $pengembalians = $q->get();

        $pdf = Pdf::loadView('petugas.laporan.pengembalian.pdf', [
            'pengembalians' => $pengembalians,
            'from' => $request->from,
            'to' => $request->to,
        ]);

        return $pdf->download('laporan-pengembalian.pdf');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(
            new PengembalianExport($request->only(['from', 'to'])),
            'laporan-pengembalian.xlsx'
        );
    }
}
