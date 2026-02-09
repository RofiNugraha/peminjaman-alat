@extends('layouts.app')

@section('content')
<div id="mainContent" class="main-content">
    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
            <h4 class="fw-bold mb-0">Manajemen Kategori</h4>

            <div class="d-flex gap-2 align-items-center">
                <input type="text" id="search" class="form-control" placeholder="Cari kategori..." style="width: 220px">

                <select id="direction" class="form-select" style="width: 120px">
                    <option value="desc">Terbaru</option>
                    <option value="asc">Terlama</option>
                </select>

                <a href="{{ route('kategori.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg"></i> Tambah Kategori
                </a>
            </div>
        </div>


        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div id="kategoriTable">
                    @include('admin.kategori.partials.table')
                </div>

            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let debounceTimer = null;

function fetchKategori(page = 1) {
    const params = {
        search: document.getElementById('search')?.value ?? '',
        direction: document.getElementById('direction')?.value ?? 'desc',
        per_page: document.getElementById('per_page')?.value ?? 10,
        page: page
    };

    const query = new URLSearchParams(params).toString();

    fetch(`{{ route('kategori.index') }}?${query}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.text())
        .then(html => {
            document.getElementById('kategoriTable').innerHTML = html;
            bindKategoriEvents();
        });
}

function bindKategoriEvents() {
    document.querySelectorAll('#kategoriTable .pagination a').forEach(link => {
        link.onclick = function(e) {
            e.preventDefault();
            const page = new URL(this.href).searchParams.get('page');
            fetchKategori(page);
        };
    });

    const perPage = document.getElementById('per_page');
    if (perPage) {
        perPage.onchange = () => fetchKategori(1);
    }
}

document.getElementById('search')?.addEventListener('keyup', () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => fetchKategori(1), 400);
});

document.getElementById('direction')?.addEventListener('change', () => fetchKategori(1));

document.addEventListener('DOMContentLoaded', () => {
    bindKategoriEvents();
});
</script>
@endpush
@endsection