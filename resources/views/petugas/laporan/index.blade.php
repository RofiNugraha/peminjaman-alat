@extends('layouts.app')

@section('title','Laporan Peminjaman')

@section('content')

<div class="page-header mb-4">
    <h3 class="mb-1">Laporan Peminjaman</h3>
    <p class="text-muted mb-0">Filter dan export data peminjaman</p>
</div>

<div class="card mb-3">
    <div class="card-body">

        <form method="GET" class="row g-3">

            <div class="col-md-3">
                <label class="form-label small">Tanggal Berdasarkan</label>
                <select name="date_type" class="form-select">
                    <option value="tgl_pinjam" @selected(request('date_type')=='tgl_pinjam' )>Tanggal Pinjam</option>
                    <option value="tgl_kembali" @selected(request('date_type')=='tgl_kembali' )>Tanggal Kembali</option>
                    <option value="approved_at" @selected(request('date_type')=='approved_at' )>Tanggal Disetujui
                    </option>
                    <option value="tgl_dikembalikan" @selected(request('date_type')=='tgl_dikembalikan' )>Tanggal
                        Pengembalian</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label small">Dari Tanggal</label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>

            <div class="col-md-3">
                <label class="form-label small">Sampai Tanggal</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>

            <div class="col-md-3">
                <label class="form-label small">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua</option>
                    @foreach(['disetujui','dikembalikan','menunggu','ditolak','dibatalkan','kadaluarsa'] as $s)
                    <option value="{{ $s }}" @selected(request('status')==$s)>
                        {{ ucfirst($s) }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label small">Status Denda</label>
                <select name="status_denda" class="form-select">
                    <option value="">Semua</option>
                    <option value="tidak_ada" @selected(request('status_denda')=='tidak_ada' )>Tidak Ada</option>
                    <option value="belum" @selected(request('status_denda')=='belum' )>Belum Lunas</option>
                    <option value="lunas" @selected(request('status_denda')=='lunas' )>Lunas</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label small">Alat</label>
                <select name="alat_id" class="form-select">
                    <option value="">Semua</option>
                    @foreach($alats as $alat)
                    <option value="{{ $alat->id }}" @selected(request('alat_id')==$alat->id)>
                        {{ $alat->nama_alat }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 d-flex justify-content-between align-items-end mt-2">
                <div class="d-flex gap-2">
                    <button class="btn btn-primary btn-sm">
                        Terapkan Filter
                    </button>

                    <a href="{{ route('petugas.laporan.index') }}" class="btn btn-light border btn-sm">
                        Reset
                    </a>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('petugas.laporan.pdf', request()->all()) }}" class="btn btn-danger btn-sm">
                        Export PDF
                    </a>

                    <a href="{{ route('petugas.laporan.excel', request()->all()) }}" class="btn btn-success btn-sm">
                        Export Excel
                    </a>
                </div>
            </div>

        </form>

    </div>
</div>

<div class="card mb-3">
    <div class="card-body">

        <div class="row text-center">
            <div class="col-md-4 mb-3">
                <small class="text-muted">Total Transaksi</small>
                <h5 class="fw-semibold mb-0">{{ $totalTransaksi }}</h5>
            </div>

            <div class="col-md-4 mb-3">
                <small class="text-muted">Total Denda</small>
                <h5 class="fw-semibold mb-0">
                    Rp {{ number_format($totalDenda,0,',','.') }}
                </h5>
            </div>

            <div class="col-md-4 mb-3">
                <small class="text-muted">Peminjaman Telat</small>
                <h5 class="fw-semibold mb-0">
                    {{ $totalTelat }}
                </h5>
            </div>
        </div>

    </div>
</div>

<div class="card">
    <div class="card-body p-0">

        <div class="table-responsive">
            <table class="table table-modern align-middle mb-0">
                <thead>
                    <tr>
                        <th width="60">No</th>
                        <th>Kode</th>
                        <th>Peminjam</th>
                        <th>Alat</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Denda</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $row)
                    <tr>
                        <td class="text-muted">
                            {{ ($data->currentPage()-1)*$data->perPage()+$index+1 }}
                        </td>

                        <td class="fw-semibold">
                            {{ $row->kode_peminjaman }}
                        </td>

                        <td>{{ $row->user->nama }}</td>

                        <td>
                            @foreach($row->items as $item)
                            <div class="small">
                                {{ $item->alat->nama_alat }} ({{ $item->qty }})
                            </div>
                            @endforeach
                        </td>

                        <td class="text-muted small">
                            {{ $row->tgl_pinjam->format('d M Y') }}<br>
                            s/d {{ $row->tgl_kembali->format('d M Y') }}
                        </td>

                        <td>
                            @php
                            $colors = [
                            'disetujui' => 'success',
                            'dikembalikan' => 'primary',
                            'menunggu' => 'warning',
                            'ditolak' => 'danger',
                            'dibatalkan' => 'secondary',
                            'kadaluarsa' => 'dark'
                            ];
                            @endphp

                            <span
                                class="badge bg-{{ $colors[$row->status] ?? 'secondary' }} bg-opacity-10 text-{{ $colors[$row->status] ?? 'secondary' }}">
                                {{ ucfirst($row->status) }}
                            </span>
                        </td>

                        <td>
                            <div>
                                Rp {{ number_format($row->total_denda,0,',','.') }}
                            </div>
                            <small class="text-muted">
                                {{ strtoupper($row->status_denda) }}
                            </small>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            Tidak ada data ditemukan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex flex-wrap justify-content-between align-items-center p-3 border-top">

            <div class="d-flex align-items-center gap-2">
                <span class="small text-muted">Data per halaman</span>
                <select onchange="location = this.value" class="form-select form-select-sm w-auto">
                    @foreach([5,10,25,50,100] as $size)
                    <option value="{{ request()->fullUrlWithQuery(['per_page'=>$size]) }}"
                        @selected(request('per_page',10)==$size)>
                        {{ $size }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                {{ $data->links('vendor.pagination.custom') }}
            </div>

        </div>

    </div>
</div>

@endsection