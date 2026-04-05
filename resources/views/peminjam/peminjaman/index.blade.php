@extends('layouts.app')

@section('title', 'Peminjaman Saya')

@section('content')
<div class="page-header mb-4">
    <h3 class="mb-1">Peminjaman Alat Saya</h3>
    <p class="mb-0">Kelola dan pantau status peminjaman alat</p>
</div>

@if(session('success'))
<div class="alert alert-success mb-3">
    {{ session('success') }}
</div>
@endif

<div class="card mb-3">
    <div class="card-body d-flex flex-wrap gap-3 align-items-end">

        <div>
            <label class="form-label small">Cari</label>
            <input type="text" id="search" class="form-control" placeholder="Nama alat">
        </div>

        <div>
            <label class="form-label small">Status</label>
            <select id="status" class="form-select">
                <option value="">Semua</option>
                <option value="menunggu">Menunggu</option>
                <option value="disetujui">Disetujui</option>
                <option value="ditolak">Ditolak</option>
                <option value="dikembalikan">Dikembalikan</option>
                <option value="kadaluarsa">Kadaluarsa</option>
                <option value="dibatalkan">Dibatalkan</option>
            </select>
        </div>

        <div>
            <label class="form-label small">Dari</label>
            <input type="date" id="date_from" class="form-control">
        </div>

        <div>
            <label class="form-label small">Sampai</label>
            <input type="date" id="date_to" class="form-control">
        </div>

        <div>
            <label class="form-label small">Urutan</label>
            <select id="direction" class="form-select">
                <option value="desc">Terbaru</option>
                <option value="asc">Terlama</option>
            </select>
        </div>

    </div>
</div>

<div class="card">
    <div class="card-body p-0">

        @if($peminjamanItems->count())
        <div id="peminjamanTable">
            @include('peminjam.peminjaman.partials.table')
        </div>
        @else
        <div class="text-center text-muted py-5">
            Belum ada peminjaman
        </div>
        @endif

    </div>
</div>

@push('scripts')
<script>
let debounceTimer = null;

function fetchPeminjaman(page = 1) {
    const params = {
        search: document.getElementById('search').value,
        status: document.getElementById('status').value,
        date_from: document.getElementById('date_from').value,
        date_to: document.getElementById('date_to').value,
        direction: document.getElementById('direction').value,
        per_page: document.getElementById('per_page')?.value ?? 10,
        page: page
    };

    const query = new URLSearchParams(params).toString();

    fetch(`{{ route('peminjam.peminjaman.index') }}?${query}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.text())
        .then(html => {
            document.getElementById('peminjamanTable').innerHTML = html;
            bindEvents();
        });
}

function bindEvents() {
    document.querySelectorAll('#peminjamanTable .pagination a').forEach(link => {
        link.onclick = function(e) {
            e.preventDefault();
            const page = new URL(this.href).searchParams.get('page');
            fetchPeminjaman(page);
        };
    });

    document.getElementById('per_page')?.addEventListener('change', () => fetchPeminjaman(1));
}

document.getElementById('search').addEventListener('keyup', () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => fetchPeminjaman(1), 400);
});

document.getElementById('status').addEventListener('change', () => fetchPeminjaman(1));
document.getElementById('direction').addEventListener('change', () => fetchPeminjaman(1));
document.getElementById('date_from').addEventListener('change', () => fetchPeminjaman(1));
document.getElementById('date_to').addEventListener('change', () => fetchPeminjaman(1));

document.addEventListener('DOMContentLoaded', bindEvents);
</script>
@endpush

@endsection