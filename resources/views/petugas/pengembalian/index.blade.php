@extends('layouts.app')

@section('title', 'Monitoring Pengembalian')

@section('content')
<div id="mainContent" class="main-content">
    <div class="container-fluid px-4 py-4">
        <h4 class="fw-bold mb-3">Daftar Pengembalian Alat</h4>

        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="d-flex gap-2 flex-wrap mb-3">
            <input type="text" id="search" class="form-control" placeholder="Cari nama atau alat..."
                style="width:200px">

            <input type="date" id="date_from" class="form-control" style="width:160px">
            <input type="date" id="date_to" class="form-control" style="width:160px">

            <select id="direction" class="form-select" style="width:120px">
                <option value="desc">Terbaru</option>
                <option value="asc">Terlama</option>
            </select>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body" id="pengembalianTable">
                @include('petugas.pengembalian.partials.table', ['peminjamans' => $peminjamans, 'perPage' => $perPage])
            </div>
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