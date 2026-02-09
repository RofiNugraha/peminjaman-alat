<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriAlatController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $kategoris = Kategori::withCount([
            'alat as total_stok' => function ($q) {
                $q->where('stok', '>', 0);
            }
        ])
        ->when($search, function ($query) use ($search) {
            $query->where('nama_kategori', 'like', "%{$search}%");
        })
        ->orderBy('nama_kategori')
        ->get();

        if ($request->ajax()) {
            return view('peminjam.kategori._list', compact('kategoris'))->render();
        }

        return view('peminjam.kategori.index', compact('kategoris'));
    }

    public function show(Request $request, Kategori $kategori)
    {
        $search  = trim($request->get('search'));
        $filterKondisi = $request->get('kondisi');

        $query = $kategori->alat();

        if ($search !== '') {
            $query->where('nama_alat', 'LIKE', '%' . $search . '%');
        }

        if (in_array($filterKondisi, ['Baik', 'Rusak'])) {
            $query->where('kondisi', $filterKondisi);
        }

        $alats = $query
            ->orderBy('nama_alat')
            ->get();

        if ($request->ajax()) {
            return view('peminjam.kategori._alat_list', compact('alats'))->render();
        }

        return view('peminjam.kategori.show', compact('kategori', 'alats'));
    }
}