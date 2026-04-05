@extends('layouts.app')

@section('title', 'Pilih Kategori Alat')

@section('content')
<div class="page-header mb-4">
    <h3 class="mb-1">Kategori Alat</h3>
    <p class="mb-0">Pilih kategori alat yang tersedia</p>
</div>

<div class="card mb-3">
    <div class="card-body d-flex flex-wrap justify-content-between align-items-end gap-3">

        <div class="d-flex flex-wrap gap-3 align-items-end">

            <div>
                <label class="form-label small">Cari Kategori</label>
                <input type="text" id="search" class="form-control" placeholder="Nama kategori..."
                    value="{{ request('search') }}">
            </div>

            <div>
                <label class="form-label small">Data per halaman</label>
                <select id="perPage" class="form-select">
                    @foreach([4,8,12,16] as $size)
                    <option value="{{ $size }}" @selected($perPage==$size)>{{ $size }}</option>
                    @endforeach
                </select>
            </div>

        </div>

    </div>
</div>

<div class="card">
    <div class="card-body">
        <div id="kategori-container">
            @include('peminjam.kategori._list')
        </div>
    </div>
</div>

<script>
let timeout = null;

let delay;

function fetchKategori(url = null) {
    const search = document.getElementById('search').value;
    const perPage = document.getElementById('perPage').value;

    let endpoint;

    if (url) {
        const u = new URL(url);
        u.searchParams.set('search', search);
        u.searchParams.set('per_page', perPage);
        endpoint = u.toString();
    } else {
        endpoint = `{{ route('peminjam.kategori.index') }}?search=${search}&per_page=${perPage}`;
    }

    fetch(endpoint, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.text())
        .then(html => {
            document.getElementById('kategori-container').innerHTML = html;
        });
}

document.getElementById('search').addEventListener('input', () => {
    clearTimeout(delay);
    delay = setTimeout(() => fetchKategori(), 300);
});

document.getElementById('perPage').addEventListener('change', () => {
    fetchKategori();
});

document.addEventListener('click', function(e) {
    const link = e.target.closest('.pagination a');
    if (link) {
        e.preventDefault();
        fetchKategori(link.href);
    }
});
</script>
@endsection