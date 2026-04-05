<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataSiswa;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DataSiswaImport;
use Illuminate\Support\Facades\DB;

class DataSiswaController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);
        if (!in_array($perPage, [5, 10, 25, 50, 100])) {
            $perPage = 10;
        }

        $search = $request->search;
        $status = $request->status;
        $tahunAjaran = $request->tahun_ajaran;
        
        $order = in_array($request->order, ['asc', 'desc']) ? $request->order : 'desc';

        $data = DataSiswa::query()
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('nama', 'like', "%{$search}%")
                        ->orWhere('nisn', 'like', "%{$search}%")
                        ->orWhere('kelas', 'like', "%{$search}%")
                        ->orWhere('jurusan', 'like', "%{$search}%")
                        ->orWhere('tahun_angkatan', 'like', "%{$search}%");
                });
            })
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($tahunAjaran, fn ($q) => $q->where('tahun_ajaran', $tahunAjaran))
            ->orderBy('created_at', $order)
            ->paginate($perPage)
            ->withQueryString()
            ->onEachSide(1);

        if ($request->ajax()) {
            return view('admin.data_siswa.partials.table', compact('data', 'perPage'))->render();
        }

        return view('admin.data_siswa.index', compact('data', 'perPage'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
            'tahun_ajaran' => 'required|string|regex:/^\d{4}\/\d{4}$/'
        ], [
            'file.required' => 'File wajib diisi',
            'file.mimes' => 'Format wajib xlsx atau xls',

            'tahun_ajaran.required' => 'Tahun ajaran wajib diisi',
            'tahun_ajaran.regex' => 'Format tidak sesuai. Contoh sesuai: 2025/2026',
        ]);

        DB::beginTransaction();

        try {
            DataSiswa::query()->update([
                'status' => 'nonaktif'
            ]);

            $import = new DataSiswaImport($request->tahun_ajaran);
            Excel::import($import, $request->file);

            if ($import->failures()->isNotEmpty()) {
                DB::rollBack();

                return back()->with([
                    'failures' => $import->failures(),
                    'error' => 'Sebagian data gagal diimport. Periksa detail di bawah.'
                ]);
            }

            DB::commit();

            return back()->with('success', 'Data berhasil disinkronisasi');

        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}