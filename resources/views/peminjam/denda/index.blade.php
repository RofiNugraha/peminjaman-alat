@extends('layouts.app')

@section('title', 'Monitoring Denda')

@section('content')
<div class="page-header mb-4">
    <h3 class="mb-1">Denda Saya</h3>
    <p class="mb-0 text-muted">Pantau status dan detail denda peminjaman Anda</p>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card mb-3">
    <div class="card-body d-flex flex-wrap gap-3 align-items-end">

        <div>
            <label class="form-label small">Cari</label>
            <input type="text" id="search" class="form-control" placeholder="Kode / nama alat">
        </div>

        <div>
            <label class="form-label small">Status</label>
            <select id="status_denda" class="form-select">
                <option value="">Semua</option>
                <option value="belum">Belum Dibayar</option>
                <option value="lunas">Lunas</option>
                <option value="tidak_ada">Tidak Ada</option>
            </select>
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
    <div class="card-body p-0" id="dendaTable">
        @include('peminjam.denda.partials.table')
    </div>
</div>

@push('scripts')
<script>
let debounceTimer = null;

function getPerPage() {
    return document.getElementById('per_page')?.value ?? 10;
}

function fetchDenda(page = 1) {
    const params = {
        search: document.getElementById('search').value,
        status_denda: document.getElementById('status_denda').value,
        direction: document.getElementById('direction').value,
        per_page: getPerPage(),
        page: page
    };

    const query = new URLSearchParams(params).toString();

    fetch(`{{ route('peminjam.denda.index') }}?${query}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.text())
        .then(html => {
            document.getElementById('dendaTable').innerHTML = html;
            bindPagination();
        });
}

function bindPagination() {
    document.querySelectorAll('#dendaTable .pagination a').forEach(link => {
        link.onclick = function(e) {
            e.preventDefault();
            const page = new URL(this.href).searchParams.get('page');
            fetchDenda(page);
        };
    });

    document.getElementById('per_page')
        ?.addEventListener('change', () => fetchDenda(1));
}

document.addEventListener('input', e => {
    if (['search', 'status_denda', 'direction'].includes(e.target.id)) {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => fetchDenda(1), 400);
    }
});

document.addEventListener('DOMContentLoaded', bindPagination);
</script>
@endpush
@endsection