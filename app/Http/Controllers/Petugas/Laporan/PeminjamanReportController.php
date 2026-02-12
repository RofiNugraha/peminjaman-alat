<?php

namespace App\Http\Controllers\Petugas\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PeminjamanExport;

class PeminjamanReportController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'from'   => 'nullable|date',
            'to'     => 'nullable|date|after_or_equal:from',
            'status' => 'nullable|in:menunggu,disetujui,ditolak,dibatalkan,kadaluarsa,dikembalikan',
            'type'   => 'nullable|in:harian,bulanan',
        ]);

        $from   = $request->from;
        $to     = $request->to;
        $status = $request->status;
        $type   = $request->type ?? 'harian';

        $peminjamans = Peminjaman::with(['user','pengembalian','pengembalian.denda'])
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($from && $to, fn($q) =>
                $q->whereBetween('tgl_pinjam', [
                    Carbon::parse($from)->startOfDay(),
                    Carbon::parse($to)->endOfDay()
                ])
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($type === 'bulanan') {

            $rekap = Peminjaman::selectRaw("
                    YEAR(tgl_pinjam) as tahun,
                    MONTH(tgl_pinjam) as bulan,
                    COUNT(*) as total_peminjaman
                ")
                ->when($status, fn($q) => $q->where('status', $status))
                ->when($from && $to, fn($q) =>
                    $q->whereBetween('tgl_pinjam', [
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

            $rekap = Peminjaman::selectRaw("
                    DATE(tgl_pinjam) as tanggal,
                    COUNT(*) as total_peminjaman
                ")
                ->when($status, fn($q) => $q->where('status', $status))
                ->when($from && $to, fn($q) =>
                    $q->whereBetween('tgl_pinjam', [
                        Carbon::parse($from)->startOfDay(),
                        Carbon::parse($to)->endOfDay()
                    ])
                )
                ->groupBy('tanggal')
                ->orderBy('tanggal', 'desc')
                ->paginate(10)
                ->withQueryString();
        }

        return view('petugas.laporan.peminjaman.index', compact(
            'peminjamans',
            'rekap',
            'type'
        ));
    }

    public function exportPdf(Request $request) {
        $q = Peminjaman::with([ 'user', 'pengembalian', 'pengembalian.denda' ]);
        
        if ($request->from) {
            $q->whereDate('tgl_pinjam', '>=', $request->from);
        }
        
        if ($request->to) {
            $q->whereDate('tgl_pinjam', '<=', $request->to);
        }
        
        if ($request->status) {
            $q->where('status', $request->status);
        }
        
        $peminjamans = $q->get();
        $pdf = Pdf::loadView( 'petugas.laporan.peminjaman.pdf', [
            'peminjamans' => $peminjamans,
            'from' => $request->from,
            'to' => $request->to,
        ]);
        
        return $pdf->download('laporan-peminjaman.pdf');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(
            new PeminjamanExport($request->only(['from','to','status'])),
            'laporan-peminjaman.xlsx'
        );
    }
}