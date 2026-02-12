<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DendaController extends Controller
{
    public function index(Request $request)
{
    $perPage = (int) $request->get('per_page', 10);
    if (!in_array($perPage, [5,10,25,50,100])) {
        $perPage = 10;
    }

    $order = $request->direction === 'asc' ? 'asc' : 'desc';

    $peminjamans = Peminjaman::with([
            'items.alat',
            'pengembalian.denda'
        ])
        ->where('id_user', Auth::id())
        ->where('total_denda', '>', 0)
        ->orderBy('updated_at', $order)
        ->paginate($perPage)
        ->withQueryString();

    if ($request->ajax()) {
        return view('peminjam.denda.partials.table', compact('peminjamans', 'perPage'))->render();
    }

    return view('peminjam.denda.index', compact('peminjamans', 'perPage'));
}
}