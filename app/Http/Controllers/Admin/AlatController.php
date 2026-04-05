<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AlatController extends Controller
{
    public function index(Request $request)
    {
        $perPage  = in_array($request->per_page, [5, 10, 25, 50, 100]) ? $request->per_page : 10;
        $search   = $request->search;
        $kategori = $request->kategori;
        $order    = in_array($request->order, ['asc', 'desc']) ? $request->order : 'desc';

        $query = Alat::with('kategoris');

        if ($search) {
            $query->where('nama_alat', 'like', "%{$search}%");
        }

        if ($kategori) {
            $query->where('id_kategori', $kategori);
        }

        $alats = $query
            ->orderBy('created_at', $order)
            ->paginate($perPage)
            ->withQueryString();

        $kategoris = Kategori::orderBy('nama_kategori')->get();

        if ($request->ajax()) {
            return view('admin.alat.partials.table', compact('alats', 'perPage'))->render();
        }

        return view('admin.alat.index', compact('alats', 'kategoris', 'perPage'));
    }

    public function create()
    {
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        return view('admin.alat.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kategori'   => 'required|exists:kategoris,id',
            'nama_alat'     => 'required|string|max:100',
            'stok'          => 'required|integer|min:0',
            'denda_per_hari' => 'required|integer|min:0',
            'gambar'        => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'id_kategori.required' => 'Kategori wajib dipilih.',
            'id_kategori.exists'   => 'Kategori tidak valid.',

            'nama_alat.required' => 'Nama alat wajib diisi.',
            'nama_alat.string'   => 'Nama alat harus berupa teks.',
            'nama_alat.max'      => 'Nama alat maksimal 100 karakter.',

            'stok.required' => 'Stok wajib diisi.',
            'stok.integer'  => 'Stok tidak valid dan harus berupa angka.',
            'stok.min'      => 'Stok tidak boleh kurang dari 0.',

            'denda_per_hari.required' => 'Harga Denda wajib diisi.',
            'denda_per_hari.integer'  => 'Harga Denda tidak valid dan harus berupa angka.',
            'denda_per_hari.min'      => 'Harga Denda tidak boleh kurang dari 0.',

            'gambar.required' => 'Gambar alat wajib diunggah.',
            'gambar.image'    => 'File harus berupa gambar.',
            'gambar.mimes'    => 'Format gambar harus JPG, JPEG, atau PNG.',
            'gambar.max'      => 'Ukuran gambar maksimal 2 MB.',
        ]);

        $path = $request->file('gambar')->store('alat', 'public');

        $alat = Alat::create([
            'id_kategori'       => $request->id_kategori,
            'nama_alat'         => $request->nama_alat,
            'stok'              => $request->stok,
            'denda_per_hari'    => $request->denda_per_hari,
            'gambar'            => $path,
        ]);

        catat_log(Auth::user()->nama . ' menambahkan alat ' . $alat->nama_alat);

        return redirect()->route('alat.index')->with('success', 'Alat berhasil ditambahkan.');
    }

    public function edit(Alat $alat)
    {
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        return view('admin.alat.edit', compact('alat', 'kategoris'));
    }

    public function show(Alat $alat)
    {
        return view('admin.alat.show', compact('alat'));
    }

    public function update(Request $request, Alat $alat)
    {
        $request->validate([
            'id_kategori'       => 'required|exists:kategoris,id',
            'nama_alat'         => 'required|string|max:100',
            'stok'              => 'required|integer|min:0',
            'denda_per_hari'    => 'required|integer|min:0',
            'gambar'            => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'id_kategori.required' => 'Kategori wajib dipilih.',
            'id_kategori.exists'   => 'Kategori tidak valid.',

            'nama_alat.required' => 'Nama alat wajib diisi.',
            'nama_alat.max'      => 'Nama alat maksimal 100 karakter.',

            'stok.required' => 'Stok wajib diisi.',
            'stok.integer'  => 'Stok tidak valid dan harus berupa angka.',
            'stok.min'      => 'Stok tidak boleh kurang dari 0.',

            'denda_per_hari.required' => 'Harga Denda wajib diisi.',
            'denda_per_hari.integer'  => 'Harga Denda tidak valid dan harus berupa angka.',
            'denda_per_hari.min'      => 'Harga Denda tidak boleh kurang dari 0.',

            'gambar.image' => 'File harus berupa gambar.',
            'gambar.mimes' => 'Format gambar harus JPG, JPEG, atau PNG.',
            'gambar.max'   => 'Ukuran gambar maksimal 2 MB.',
        ]);


        $data = $request->only([
            'id_kategori',
            'nama_alat',
            'stok',
            'denda_per_hari',
        ]);

        if ($request->hasFile('gambar')) {
            if ($alat->gambar && Storage::disk('public')->exists($alat->gambar)) {
                Storage::disk('public')->delete($alat->gambar);
            }

            $data['gambar'] = $request->file('gambar')->store('alat', 'public');
        }

        $alat->update($data);

        catat_log(Auth::user()->nama . ' mengubah data alat: ' . $alat->nama_alat);

        return redirect()->route('alat.index')->with('success', 'Alat berhasil diperbarui.');
    }

    public function destroy(Alat $alat)
    {
        if ($alat->peminjamanItems()->exists()) {
            return back()->with('error', 'Alat tidak dapat dihapus karena memiliki riwayat peminjaman.');
        }

        if ($alat->gambar && Storage::disk('public')->exists($alat->gambar)) {
            Storage::disk('public')->delete($alat->gambar);
        }

        $alat->delete();

        catat_log(Auth::user()->nama . ' menghapus alat ' . $alat->nama_alat);

        return redirect()->route('alat.index')->with('success', 'Alat berhasil dihapus.');
    }
}
