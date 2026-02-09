@extends('layouts.app')

@section('title', 'Peminjaman Saya')

@section('content')
<div id="mainContent" class="main-content">
    <div class="container-fluid px-4 py-4">
        <h4 class="fw-bold mb-3">Peminjaman Alat Saya</h4>

        <div class="d-flex gap-2 flex-wrap">
            <input type="text" id="search" class="form-control" placeholder="Cari alat..." style="width:180px">

            <select id="status" class="form-select" style="width:150px">
                <option value="">Semua Status</option>
                <option value="menunggu">Menunggu</option>
                <option value="disetujui">Disetujui</option>
                <option value="ditolak">Ditolak</option>
                <option value="dikembalikan">Dikembalikan</option>
                <option value="kadaluarsa">Kadaluarsa</option>
                <option value="dibatalkan">dibatalkan</option>
            </select>

            <input type="date" id="date_from" class="form-control" style="width:160px">
            <input type="date" id="date_to" class="form-control" style="width:160px">

            <select id="direction" class="form-select" style="width:120px">
                <option value="desc">Terbaru</option>
                <option value="asc">Terlama</option>
            </select>
        </div>

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-body">

                @if($peminjamanItems->count())
                <div id="peminjamanTable">
                    @include('peminjam.peminjaman.partials.table')
                </div>
                @else
                <div class="text-center text-muted py-4">
                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                    Belum ada peminjaman yang diajukan.
                </div>
                @endif

            </div>
        </div>
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