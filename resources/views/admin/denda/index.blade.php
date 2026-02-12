@extends('layouts.app')

@section('title', 'Monitoring Denda')

@section('content')
<div id="mainContent" class="main-content">
    <div class="container-fluid px-4 py-4">
        <h4 class="fw-bold mb-3">Monitoring Denda Peminjaman</h4>

        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="d-flex gap-2 flex-wrap mb-3">
            <input type="text" id="search" class="form-control" placeholder="Cari nama peminjam / alat"
                style="width:220px">

            <select id="status_denda" class="form-select" style="width:170px">
                <option value="">Semua Status</option>
                <option value="belum">Belum Dibayar</option>
                <option value="lunas">Lunas</option>
            </select>

            <select id="direction" class="form-select" style="width:130px">
                <option value="desc">Terbaru</option>
                <option value="asc">Terlama</option>
            </select>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body" id="dendaTable">
                @include('admin.denda.partials.table', [
                'peminjamans' => $peminjamans,
                'perPage' => $perPage
                ])
            </div>
        </div>
    </div>
</div>
@endsection

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

    fetch(`{{ route('admin.denda.index') }}?${query}`, {
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

    document.getElementById('per_page')?.addEventListener('change', () => fetchDenda(1));
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