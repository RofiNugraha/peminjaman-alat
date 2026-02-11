<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\PeminjamanItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);
        if (!in_array($perPage, [5,10,25,50,100])) {
            $perPage = 10;
        }

        $search    = $request->search;
        $status    = $request->status;
        $direction = $request->direction === 'asc' ? 'asc' : 'desc';

        $dateFrom = $request->date_from;
        $dateTo   = $request->date_to;

        $peminjamanItems = PeminjamanItem::with([
            'alat.kategori',
            'peminjaman'
        ])
        ->whereHas('peminjaman', function ($q) {
            $q->where('id_user', Auth::id());
        })

        ->when($search, function ($q) use ($search) {
            $q->whereHas('alat', function ($sub) use ($search) {
                $sub->where('nama_alat', 'like', "%{$search}%");
            });
        })

        ->when($status, function ($q) use ($status) {
            $q->whereHas('peminjaman', fn ($sub) =>
                $sub->where('status', $status)
            );
        })

        ->when($dateFrom, fn ($q) =>
            $q->whereHas('peminjaman', fn ($sub) =>
                $sub->whereDate('tgl_pinjam', '>=', $dateFrom)
            )
        )

        ->when($dateTo, fn ($q) =>
            $q->whereHas('peminjaman', fn ($sub) =>
                $sub->whereDate('tgl_pinjam', '<=', $dateTo)
            )
        )

        ->orderByDesc('created_at')
        ->paginate($perPage)
        ->withQueryString();

        if ($request->ajax()) {
        return view(
            'peminjam.peminjaman.partials.table', compact('peminjamanItems', 'perPage'))->render();
        }

        return view('peminjam.peminjaman.index', compact('peminjamanItems', 'perPage')
        );
    }

    public function create(Alat $alat)
    {
        if ($alat->stok <= 0) {
            return redirect()
                ->back()
                ->with('error', 'Stok alat habis.');
        }

        return view('peminjam.peminjaman.create', compact('alat'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_alat' => ['required', 'exists:alats,id'],
            'qty'     => ['required', 'integer', 'min:1'],
            'tgl_pinjam' => [
                'required',
                'date',
                'after_or_equal:today',
                function ($attr, $value, $fail) {
                    if (Carbon::parse($value)->isWeekend()) {
                        $fail('Tanggal pinjam tidak boleh pada hari Sabtu atau Minggu.');
                    }
                }
            ],
            'tgl_kembali' => [
                'required',
                'date',
                'after_or_equal:tgl_pinjam',
                function ($attr, $value, $fail) use ($request) {
                    $pinjam  = Carbon::parse($request->tgl_pinjam);
                    $kembali = Carbon::parse($value);

                    if ($pinjam->diffInDays($kembali) > 7) {
                        $fail('Durasi maksimal peminjaman adalah 7 hari.');
                    }

                    if ($kembali->isWeekend()) {
                        $fail('Tanggal kembali tidak boleh pada hari Sabtu atau Minggu.');
                    }
                }
            ],
        ], [
            'id_alat.required' => 'Silakan pilih alat yang ingin dipinjam.',
            'id_alat.exists' => 'Alat yang dipilih tidak ditemukan.',
            'qty.required' => 'Jumlah peminjaman wajib diisi.',
            'qty.integer' => 'Jumlah peminjaman harus berupa angka.',
            'qty.min' => 'Jumlah minimal peminjaman adalah 1.',
            'tgl_pinjam.required' => 'Tanggal pinjam wajib diisi.',
            'tgl_pinjam.date' => 'Tanggal pinjam tidak valid.',
            'tgl_pinjam.after_or_equal' => 'Tanggal pinjam tidak boleh sebelum hari ini.',
            'tgl_kembali.required' => 'Tanggal kembali wajib diisi.',
            'tgl_kembali.date' => 'Tanggal kembali tidak valid.',
            'tgl_kembali.after_or_equal' => 'Tanggal kembali tidak boleh sebelum tanggal pinjam.',
        ]);

        $alat = Alat::findOrFail($request->id_alat);

        if ($request->qty > $alat->stok) {
            return back()
                ->withErrors(['qty' => 'Jumlah yang diminta melebihi stok yang tersedia.'])
                ->withInput();
        }

        $stokMenunggu = PeminjamanItem::where('id_alat', $alat->id)
            ->whereHas('peminjaman', fn($q) => $q->where('status', 'menunggu'))
            ->sum('qty');

        if (($stokMenunggu + $request->qty) > $alat->stok) {
            return back()
                ->withErrors(['qty' => 'Sebagian stok sedang dalam proses peminjaman oleh pengguna lain. Silakan coba beberapa saat lagi.'])
                ->withInput();
        }

        DB::transaction(function () use ($request) {
            $peminjaman = Peminjaman::create([
                'id_user'       => Auth::id(),
                'tgl_pinjam'    => $request->tgl_pinjam,
                'tgl_kembali'   => $request->tgl_kembali,
                'status'        => 'menunggu',
                'total_denda'   => 0,
                'status_denda'  => 'tidak_ada',
            ]);

            PeminjamanItem::create([
                'id_peminjaman' => $peminjaman->id,
                'id_alat'       => $request->id_alat,
                'qty'           => $request->qty,
            ]);
        });

        catat_log(Auth::user()->nama . ' mengajukan peminjaman alat ' . $alat->nama_alat);

        return redirect()
            ->route('peminjam.peminjaman.index')
            ->with('success', 'Pengajuan peminjaman berhasil diajukan. Silakan menunggu persetujuan dari petugas.');
    }

    public function batal(Peminjaman $peminjaman)
    {
        if ($peminjaman->id_user !== Auth::id()) {
            abort(403);
        }

        if ($peminjaman->status !== 'menunggu') {
            return back()->with('error', 'Peminjaman tidak dapat dibatalkan.');
        }

        $peminjaman->update([
            'status' => 'dibatalkan',
        ]);

        return back()->with('success', 'Pengajuan peminjaman berhasil dibatalkan.');
    }
}