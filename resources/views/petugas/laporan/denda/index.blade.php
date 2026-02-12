@extends('layouts.app')

@section('title', 'Laporan Denda')

@section('content')
<div id="mainContent" class="main-content">
    <div class="container-fluid px-4 py-4">

        <h4 class="fw-bold mb-4">Laporan Denda</h4>
        <form method="GET" class="card shadow-sm border-0 mb-4">
            <div class="card-body d-flex flex-wrap gap-2 align-items-end">

                <div>
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="from" value="{{ request('from') }}" class="form-control">
                </div>

                <div>
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="to" value="{{ request('to') }}" class="form-control">
                </div>

                <div>
                    <label class="form-label">Status Denda</label>
                    <select name="status" class="form-select">
                        <option value="">Semua</option>
                        <option value="belum_ditindak" @selected(request('status')=='belum_ditindak' )>
                            Belum Ditindak
                        </option>
                        <option value="diingatkan" @selected(request('status')=='diingatkan' )>
                            Diingatkan
                        </option>
                        <option value="dibayar" @selected(request('status')=='dibayar' )>
                            Dibayar
                        </option>
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
                    <button class="btn btn-primary">
                        Terapkan
                    </button>
                </div>
            </div>

            <a href="{{ route('petugas.laporan.denda.pdf', request()->query()) }}" class="btn btn-danger btn-sm">
                Export PDF
            </a>

            <a href="{{ route('petugas.laporan.denda.excel', request()->query()) }}" class="btn btn-success btn-sm">
                Export Excel
            </a>
        </form>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3">
                    Rekap Denda {{ $type === 'bulanan' ? 'Bulanan' : 'Harian' }}
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
                                <th>Total Kasus</th>
                                <th>Total Denda</th>
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
                                <td>{{ $r->total_kasus }}</td>
                                <td class="fw-semibold text-danger">
                                    Rp {{ number_format($r->total_denda) }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">
                                    Tidak ada data rekap
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Detail Denda</h6>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Peminjam</th>
                                <th>Tanggal Kembali</th>
                                <th>Total Denda</th>
                                <th>Status</th>
                                <th>Dibuat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $no = ($dendas->currentPage() - 1) * $dendas->perPage() + 1;
                            @endphp

                            @forelse ($dendas as $d)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $d->pengembalian->peminjaman->user->nama }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse(
                                        $d->pengembalian->tgl_dikembalikan
                                    )->format('d M Y') }}
                                </td>
                                <td class="fw-semibold text-danger">
                                    Rp {{ number_format($d->total_denda) }}
                                </td>
                                <td>
                                    @if ($d->status === 'dibayar')
                                    <span class="badge bg-success">Dibayar</span>
                                    @elseif ($d->status === 'diingatkan')
                                    <span class="badge bg-warning text-dark">Diingatkan</span>
                                    @else
                                    <span class="badge bg-danger">Belum</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $d->created_at->format('d M Y') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">
                                    Tidak ada data denda
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $dendas->links('vendor.pagination.custom') }}
                </div>
            </div>
        </div>

    </div>
</div>
@endsection