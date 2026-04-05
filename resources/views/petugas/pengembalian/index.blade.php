@extends('layouts.app')

@section('title', 'Monitoring Pengembalian')

@section('content')
<div class="page-header mb-4">
    <h3 class="mb-1">Monitoring Pengembalian</h3>
    <p class="mb-0">Kelola dan pantau pengembalian alat</p>
</div>

@if (session('success'))
<div class="alert alert-success mb-3">{{ session('success') }}</div>
@endif

@if (session('error'))
<div class="alert alert-danger mb-3">{{ session('error') }}</div>
@endif

<!-- FILTER -->
<div class="card mb-3">
    <div class="card-body">

        <form onsubmit="return false;">
            <div class="row g-3">

                <div class="col-md-4">
                    <label class="form-label small">Pencarian</label>
                    <input type="text" id="search" class="form-control" placeholder="Nama peminjam / alat / kode">
                </div>

                <div class="col-md-3">
                    <label class="form-label small">Dari Tanggal</label>
                    <input type="date" id="date_from" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label small">Sampai Tanggal</label>
                    <input type="date" id="date_to" class="form-control">
                </div>

                <div class="col-md-2">
                    <label class="form-label small">Urutan</label>
                    <select id="direction" class="form-select">
                        <option value="desc">Terbaru</option>
                        <option value="asc">Terlama</option>
                    </select>
                </div>

            </div>
        </form>

    </div>
</div>

<!-- TABLE -->
<div class="card">
    <div class="card-body p-0">
        <div id="pengembalianTable">
            @include('petugas.pengembalian.partials.table', [
            'peminjamans' => $peminjamans,
            'perPage' => $perPage
            ])
        </div>
    </div>
</div>

@push('scripts')
<script>
let debounceTimer = null;

function getPerPage() {
    return document.getElementById('per_page')?.value ?? 10;
}

function fetchPengembalian(page = 1) {
    const params = {
        search: document.getElementById('search').value,
        date_from: document.getElementById('date_from').value,
        date_to: document.getElementById('date_to').value,
        direction: document.getElementById('direction').value,
        per_page: getPerPage(),
        page: page
    };

    const query = new URLSearchParams(params).toString();

    fetch(`{{ route('petugas.pengembalian.index') }}?${query}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.text())
        .then(html => {
            document.getElementById('pengembalianTable').innerHTML = html;
            bindPagination();
        });
}

function bindPagination() {
    document.querySelectorAll('#pengembalianTable .pagination a').forEach(link => {
        link.onclick = function(e) {
            e.preventDefault();
            const page = new URL(this.href).searchParams.get('page');
            fetchPengembalian(page);
        };
    });
    document.getElementById('per_page')?.addEventListener('change', () => fetchPengembalian(1));
}

document.addEventListener('input', e => {
    if (['search', 'date_from', 'date_to', 'direction'].includes(e.target.id)) {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => fetchPengembalian(1), 400);
    }
});

document.addEventListener('DOMContentLoaded', bindPagination);
</script>
@endpush
@endsection