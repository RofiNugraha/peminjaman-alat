<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);
        if (!in_array($perPage, [5,10,25,50,100])) {
            $perPage = 10;
        }

        $search = $request->get('search');
        $direction = $request->get('direction', 'desc');

        if (!in_array($direction, ['asc','desc'])) {
            $direction = 'desc';
        }

        $kategoris = Kategori::query()
            ->withCount('alats')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->where('nama_kategori','like',"%{$search}%")
                    ->orWhere('keterangan','like',"%{$search}%");
                });
            })
            ->orderBy('created_at', $direction)
            ->paginate($perPage)
            ->withQueryString()
            ->onEachSide(1);

        if ($request->ajax()) {
            return view('admin.kategori.partials.table', compact('kategoris','perPage'))->render();
        }

        return view('admin.kategori.index', compact('kategoris','perPage'));
    }

    public function create()
    {
        return view('admin.kategori.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategoris,nama_kategori',
            'keterangan'    => 'nullable|string|max:255',
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique'   => 'Nama kategori sudah ada.',
            'nama_kategori.max'      => 'Maksimal 100 karakter.',
            'keterangan.max'         => 'Keterangan maksimal 255 karakter.',
        ]);

        $kategori = Kategori::create($validated);

        logAktivitas(
            'Menambahkan',
            'Kategori',
            "Menambahkan kategori '{$kategori->nama_kategori}' (ID-{$kategori->id})"
        );

        return redirect()
            ->route('kategori.index')
            ->with('success','Kategori berhasil ditambahkan');
    }

    public function show(Kategori $kategori)
    {
        $kategori->loadCount('alats');

        return view('admin.kategori.show', compact('kategori'));
    }

    public function edit(Kategori $kategori)
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategoris,nama_kategori,' . $kategori->id,
            'keterangan'    => 'nullable|string|max:255',
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique'   => 'Nama kategori sudah ada.',
            'nama_kategori.max'      => 'Maksimal 100 karakter.',
            'keterangan.max'         => 'Keterangan maksimal 255 karakter.',
        ]);

        $namaLama = $kategori->nama_kategori;

        $kategori->update($validated);

        logAktivitas(
            'Mengubah',
            'Kategori',
            "Mengubah kategori '{$namaLama}' (ID-{$kategori->id}) menjadi '{$kategori->nama_kategori}'"
        );

        return redirect()->route('kategori.index')->with('success','Kategori berhasil diperbarui');
    }

    public function destroy(Kategori $kategori)
    {
        if ($kategori->alats()->count() > 0) {
            return back()->with('error','Kategori tidak dapat dihapus karena masih memiliki alat.');
        }

        $nama = $kategori->nama_kategori;
        $id   = $kategori->id;
        
        $kategori->delete();

        logAktivitas(
            'Menghapus',
            'Kategori',
            "Menghapus kategori '{$nama}' (ID-{$id})"
        );

        return redirect()->route('kategori.index')->with('success','Kategori berhasil dihapus');
    }
}