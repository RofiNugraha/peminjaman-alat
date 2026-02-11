<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PeminjamanItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);
        if (!in_array($perPage, [5,10,25,50,100])) $perPage = 10;

        $tab       = $request->get('tab', 'aktif');
        $search    = $request->search;
        $status    = $request->status;
        $direction = $request->direction === 'asc' ? 'asc' : 'desc';
        $dateFrom  = $request->date_from;
        $dateTo    = $request->date_to;

        if ($dateFrom) $dateFrom = Carbon::parse($dateFrom)->format('Y-m-d');
        if ($dateTo)   $dateTo   = Carbon::parse($dateTo)->format('Y-m-d');

        if ($dateFrom && $dateTo && $dateFrom > $dateTo) {
            [$dateFrom, $dateTo] = [$dateTo, $dateFrom];
        }

        $statusAktif = ['menunggu','disetujui'];
        $statusNonAktif = ['dibatalkan','ditolak','kadaluarsa','dikembalikan'];
        $statusFilter = $tab === 'aktif' ? $statusAktif : $statusNonAktif;

        $query = PeminjamanItem::with(['alat.kategori', 'peminjaman.user'])
            ->whereHas('peminjaman', function ($q) use ($search, $status, $dateFrom, $dateTo, $statusFilter) {
                $q->whereIn('status', $statusFilter);

                if ($search) {
                    $q->where(function ($sub) use ($search) {
                        $sub->whereHas('user', fn($u) => $u->where('nama', 'like', "%{$search}%"))
                            ->orWhereHas('items.alat', fn($a) => $a->where('nama_alat', 'like', "%{$search}%"));
                    });
                }

                if ($status && in_array($status, $statusFilter)) {
                    $q->where('status', $status);
                }

                if ($dateFrom && $dateTo) {
                    $q->whereBetween(DB::raw('DATE(tgl_pinjam)'), [$dateFrom, $dateTo]);
                } elseif ($dateFrom) {
                    $q->whereDate('tgl_pinjam', '>=', $dateFrom);
                } elseif ($dateTo) {
                    $q->whereDate('tgl_pinjam', '<=', $dateTo);
                }
            });

        $peminjamanItems = $query->orderBy('created_at', $direction)
            ->paginate($perPage)
            ->appends($request->only(['tab','search','status','date_from','date_to','direction','per_page']));

        if ($request->ajax()) {
            return view('admin.peminjaman.partials.table', compact('peminjamanItems','perPage','tab'))->render();
        }

        return view('admin.peminjaman.index', compact('peminjamanItems','perPage','tab'));
    }
}