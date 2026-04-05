@extends('layouts.app')

@section('title', 'Persetujuan Peminjaman')

@section('content')
<div class="page-header mb-4">
    <h3 class="mb-1">Persetujuan Peminjaman</h3>
    <p class="mb-0">Kelola dan proses pengajuan peminjaman alat</p>
</div>

@if(session('success'))
<div class="alert alert-success mb-3">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger mb-3">
    {{ session('error') }}
</div>
@endif

<div class="card mb-3">
    <div class="card-body d-flex flex-wrap justify-content-between align-items-end gap-3">

        <form onsubmit="return false;" class="d-flex flex-wrap gap-2 align-items-end">

            <div>
                <label class="form-label small">Cari</label>
                <input type="text" id="search" class="form-control" placeholder="Nama peminjam / alat / kode">
            </div>

            <div>
                <label class="form-label small">Status</label>
                <select id="status" class="form-select">
                    <option value="">Semua</option>
                    <option value="menunggu">Menunggu</option>
                    <option value="disetujui">Disetujui</option>
                    <option value="ditolak">Ditolak</option>
                    <option value="dibatalkan">Dibatalkan</option>
                    <option value="kadaluarsa">Kadaluarsa</option>
                    <option value="dikembalikan">Dikembalikan</option>
                </select>
            </div>

            <div>
                <label class="form-label small">Dari Tanggal</label>
                <input type="date" id="date_from" class="form-control">
            </div>

            <div>
                <label class="form-label small">Sampai Tanggal</label>
                <input type="date" id="date_to" class="form-control">
            </div>

            <div>
                <label class="form-label small">Urutan</label>
                <select id="direction" class="form-select">
                    <option value="desc">Terbaru</option>
                    <option value="asc">Terlama</option>
                </select>
            </div>

        </form>

    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div id="peminjamanTable">
            @include('petugas.peminjaman.partials.table', ['peminjamans' => $peminjamans, 'perPage' => $perPage])
        </div>
    </div>
</div>

@push('scripts')
<script>
let debounceTimer = null;

function getPerPage() {
    return document.getElementById('per_page')?.value ?? 10;
}

function fetchPeminjaman(page = 1) {
    const params = {
        search: document.getElementById('search').value,
        status: document.getElementById('status').value,
        date_from: document.getElementById('date_from').value,
        date_to: document.getElementById('date_to').value,
        direction: document.getElementById('direction').value,
        per_page: getPerPage(),
        page: page
    };

    const query = new URLSearchParams(params).toString();

    fetch(`{{ route('petugas.peminjaman.index') }}?${query}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.text())
        .then(html => {
            document.getElementById('peminjamanTable').innerHTML = html;
            bindPagination();
        });
}

function bindPagination() {
    document.querySelectorAll('#peminjamanTable .pagination a').forEach(link => {
        link.onclick = function(e) {
            e.preventDefault();
            const page = new URL(this.href).searchParams.get('page');
            fetchPeminjaman(page);
        };
    });

    document.getElementById('per_page')?.addEventListener('change', () => fetchPeminjaman(1));
}

document.addEventListener('input', e => {
    if (['search', 'status', 'date_from', 'date_to', 'direction'].includes(e.target.id)) {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => fetchPeminjaman(1), 400);
    }
});

document.addEventListener('DOMContentLoaded', bindPagination);
</script>
@endpush

@endsection