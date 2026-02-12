@extends('layouts.app')

@section('title', 'Laporan Peminjaman')

@section('content')
<div id="mainContent" class="main-content">
    <div class="container-fluid px-4 py-4">
        <h4 class="fw-bold mb-4">Laporan Peminjaman</h4>

        <form method="GET" class="card shadow-sm border-0 mb-4">
            <div class="card-body d-flex flex-wrap gap-3 align-items-end">

                <div>
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="from" value="{{ request('from') }}" class="form-control">
                </div>

                <div>
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="to" value="{{ request('to') }}" class="form-control">
                </div>

                <div>
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua</option>
                        <option value="menunggu" @selected(request('status')=='menunggu' )>Menunggu</option>
                        <option value="disetujui" @selected(request('status')=='disetujui' )>Disetujui</option>
                        <option value="ditolak" @selected(request('status')=='ditolak' )>Ditolak</option>
                        <option value="dibatalkan" @selected(request('status')=='dibatalkan' )>Dibatalkan</option>
                        <option value="kadaluarsa" @selected(request('status')=='kadaluarsa' )>Kadaluarsa</option>
                        <option value="dikembalikan" @selected(request('status')=='dikembalikan' )>Dikembalikan</option>
                    </select>
                </div>

                <div>
                    <label class="form-label">Tipe Rekap</label>
                    <select name="type" class="form-select">
                        <option value="harian" @selected($type=='harian' )>Harian</option>
                        <option value="bulanan" @selected($type=='bulanan' )>Bulanan</option>
                    </select>
                </div>

                <div class="ms-auto">
                    <button class="btn btn-primary">Terapkan</button>
                </div>
            </div>

            <div class="px-3 pb-3">
                <a href="{{ route('petugas.laporan.peminjaman.pdf', request()->query()) }}"
                    class="btn btn-danger btn-sm">
                    Export PDF
                </a>

                <a href="{{ route('petugas.laporan.peminjaman.excel', request()->query()) }}"
                    class="btn btn-success btn-sm">
                    Export Excel
                </a>
            </div>
        </form>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3">
                    Rekap Peminjaman {{ $type === 'bulanan' ? 'Bulanan' : 'Harian' }}
                </h6>

                <div class="table-responsive">
                    <table class="table table-bordered table-sm align-middle">
                        <thead class="table-light">
                            <tr>
                                @if ($type === 'bulanan')
                                <th>Bulan</th>
                                @else
                                <th>Tanggal</th>
                                @endif
                                <th>Total Peminjaman</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rekap as $r)
                            <tr>
                                <td>
                                    @if ($type === 'bulanan')
                                    {{ $r->bulan }}/{{ $r->tahun }}
                                    @else
                                    {{ \Carbon\Carbon::parse($r->tanggal)->format('d M Y') }}
                                    @endif
                                </td>
                                <td class="fw-semibold">
                                    {{ $r->total_peminjaman }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted">
                                    Tidak ada data rekap
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $rekap->links('vendor.pagination.custom') }}
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Detail Peminjaman</h6>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Peminjam</th>
                                <th>Tanggal Pinjam</th>
                                <th>Tanggal Kembali</th>
                                <th>Status</th>
                                <th>Status Denda</th>
                                <th>Total Denda</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $no = ($peminjamans->currentPage() - 1) * $peminjamans->perPage() + 1;
                            @endphp

                            @forelse ($peminjamans as $p)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $p->user->nama ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($p->tgl_pinjam)->format('d M Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($p->tgl_kembali)->format('d M Y') }}</td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ ucfirst($p->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if ($p->status_denda === 'lunas')
                                    <span class="badge bg-success">Lunas</span>
                                    @elseif ($p->status_denda === 'belum')
                                    <span class="badge bg-danger">Belum</span>
                                    @else
                                    <span class="badge bg-light text-dark">Tidak Ada</span>
                                    @endif
                                </td>
                                <td class="fw-semibold text-danger">
                                    Rp {{ number_format($p->total_denda) }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-3">
                                    Tidak ada data peminjaman
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $peminjamans->links('vendor.pagination.custom') }}
                </div>
            </div>
        </div>

    </div>
</div>
@endsection