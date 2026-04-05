@extends('layouts.app')

@section('content')
<div class="page-header mb-4">
    <h3 class="mb-1">Manajemen Kategori</h3>
    <p class="mb-0">Kelola data kategori alat secara terpusat</p>
</div>

<div class="card mb-3">
    <div class="card-body d-flex flex-wrap justify-content-between align-items-end gap-3">

        <form onsubmit="return false;" class="d-flex flex-wrap gap-2 align-items-end">

            <div>
                <label class="form-label small">Cari</label>
                <input type="text" id="search" class="form-control" placeholder="Nama atau keterangan">
            </div>

            <div>
                <label class="form-label small">Urutan</label>
                <select id="direction" class="form-select">
                    <option value="desc">Terbaru</option>
                    <option value="asc">Terlama</option>
                </select>
            </div>

        </form>

        <a href="{{ route('kategori.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i>
            Tambah Kategori
        </a>

    </div>
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

<div class="card">
    <div class="card-body p-0">
        <div id="kategoriTable">
            @include('admin.kategori.partials.table')
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

document.addEventListener('click', function(e) {
    if (e.target.closest('.btn-delete')) {
        const btn = e.target.closest('.btn-delete');
        const url = btn.dataset.url;

        Swal.fire({
            title: 'Yakin?',
            text: 'Data tidak dapat dikembalikan',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = url;

                form.innerHTML = `
                    @csrf
                    @method('DELETE')
                `;

                document.body.appendChild(form);
                form.submit();
            }
        });
    }
});
</script>
@endpush
@endsection