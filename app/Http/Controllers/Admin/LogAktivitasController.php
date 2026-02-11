<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LogAktivitasController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);
        if (!in_array($perPage, [5,10,25,50,100])) $perPage = 10;

        $search    = $request->search;
        $direction = $request->direction === 'asc' ? 'asc' : 'desc';
        $dateFrom  = $request->date_from;
        $dateTo    = $request->date_to;

        if ($dateFrom) $dateFrom = Carbon::parse($dateFrom)->format('Y-m-d');
        if ($dateTo)   $dateTo   = Carbon::parse($dateTo)->format('Y-m-d');

        if ($dateFrom && $dateTo && $dateFrom > $dateTo) {
            [$dateFrom, $dateTo] = [$dateTo, $dateFrom];
        }

        $query = LogAktivitas::with('user')
            ->when($search, function ($q) use ($search) {
                $q->whereHas('user', fn($u) => $u->where('nama', 'like', "%{$search}%"))
                ->orWhere('aktivitas', 'like', "%{$search}%");
            })
            ->when($dateFrom && $dateTo, fn($q) =>
                $q->whereBetween(DB::raw('DATE(waktu)'), [$dateFrom, $dateTo])
            )
            ->when($dateFrom && !$dateTo, fn($q) =>
                $q->whereDate('waktu', '>=', $dateFrom)
            )
            ->when(!$dateFrom && $dateTo, fn($q) =>
                $q->whereDate('waktu', '<=', $dateTo)
            )
            ->orderBy('waktu', $direction);

        $logs = $query->paginate($perPage)
            ->appends($request->only(['search','date_from','date_to','direction','per_page']));
        if ($request->ajax()) {
            return view('admin.log_aktivitas.partials.table', compact('logs','perPage'))->render();
        }

        return view('admin.log_aktivitas.index', compact('logs','perPage'));
    }
}