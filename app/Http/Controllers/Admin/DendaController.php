<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class DendaController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);
        if (!in_array($perPage, [5,10,25,50,100])) {
            $perPage = 10;
        }

        $search   = $request->search;
        $status   = $request->status_denda;
        $order    = $request->direction === 'asc' ? 'asc' : 'desc';

        $peminjamans = Peminjaman::with([
                'user',
                'items.alat',
                'pengembalian.denda'
            ])
            ->where('total_denda', '>', 0)
            ->when($status, fn ($q) =>
                $q->where('status_denda', $status)
            )
            ->when($search, function ($q) use ($search) {
                $q->whereHas('user', fn ($u) =>
                    $u->where('nama', 'like', "%{$search}%")
                )->orWhereHas('items.alat', fn ($a) =>
                    $a->where('nama_alat', 'like', "%{$search}%")
                );
            })
            ->orderBy('updated_at', $order)
            ->paginate($perPage)
            ->withQueryString();

        if ($request->ajax()) {
            return view('admin.denda.partials.table', compact('peminjamans', 'perPage'))->render();
        }

        return view('admin.denda.index', compact('peminjamans', 'perPage'));
    }
}