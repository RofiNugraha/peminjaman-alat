@extends('layouts.app')

@section('title', 'Laporan Pengembalian')

@section('content')
<div id="mainContent" class="main-content">
    <div class="container-fluid px-4 py-4">
        <h4 class="fw-bold mb-4">Laporan Pengembalian</h4>

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
                <a href="{{ route('petugas.laporan.pengembalian.pdf', request()->query()) }}"
                    class="btn btn-danger btn-sm">Export PDF</a>
                <a href="{{ route('petugas.laporan.pengembalian.excel', request()->query()) }}"
                    class="btn btn-success btn-sm">Export Excel</a>
            </div>
        </form>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3">
                    Rekap Pengembalian {{ $type === 'bulanan' ? 'Bulanan' : 'Harian' }}
                </h6>

                <div class="table-responsive">
                    <table class="table table-bordered table-sm align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>{{ $type === 'bulanan' ? 'Bulan' : 'Tanggal' }}</th>
                                <th>Total Pengembalian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rekap as $r)
                            <tr>
                                <td>
                                    {{ $type === 'bulanan'
                                    ? $r->bulan.'/'.$r->tahun
                                    : \Carbon\Carbon::parse($r->tanggal)->format('d M Y') }}
                                </td>
                                <td class="fw-semibold">{{ $r->total_pengembalian }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted">Tidak ada data rekap</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $rekap->links('vendor.pagination.custom') }}</div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Detail Pengembalian</h6>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Peminjam</th>
                                <th>Tgl Pinjam</th>
                                <th>Tgl Kembali</th>
                                <th>Tgl Dikembalikan</th>
                                <th>Hari Telat</th>
                                <th>Total Denda</th>
                                <th>Status Denda</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $no = ($pengembalians->currentPage() - 1) * $pengembalians->perPage() + 1;
                            @endphp

                            @forelse ($pengembalians as $k)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $k->peminjaman->user->nama ?? '-' }}</td>
                                <td>{{ $k->peminjaman->tgl_pinjam }}</td>
                                <td>{{ $k->peminjaman->tgl_kembali }}</td>
                                <td>{{ $k->tgl_dikembalikan }}</td>
                                <td>{{ $k->hari_telat }}</td>
                                <td>Rp {{ number_format(optional($k->denda)->total_denda ?? 0) }}</td>
                                <td>{{ optional($k->denda)->status ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">Tidak ada data pengembalian</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">{{ $pengembalians->links('vendor.pagination.custom') }}</div>
            </div>
        </div>
    </div>
</div>
@endsection