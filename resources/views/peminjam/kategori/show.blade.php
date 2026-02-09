@extends('layouts.app')

@section('title', 'Daftar Alat')

@section('content')
<div id="mainContent" class="main-content">
    <div class="container-fluid px-4 py-4">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('peminjam.kategori.index') }}" class="btn btn-light me-3">Kembali</a>
            <h4 class="fw-bold mb-3">
                Alat Kategori: {{ $kategori->nama_kategori }}
            </h4>

        </div>
        <div class="row mb-4">
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" id="search" class="form-control" placeholder="Cari nama alat...">
                </div>

                <div class="col-md-4">
                    <select id="filter-kondisi" class="form-select">
                        <option value="">Semua Kondisi</option>
                        <option value="Baik">Baik</option>
                        <option value="Rusak">Rusak</option>
                    </select>
                </div>
            </div>

        </div>

        @if($alats->isEmpty())
        <div class="alert alert-warning">
            Tidak ada alat pada kategori ini.
        </div>
        @else

        <div id="alat-container">
            @include('peminjam.kategori._alat_list', ['alats' => $alats])
        </div>
        @endif
    </div>
</div>

<script>
let debounce;

function fetchAlat() {
    const search = document.getElementById('search').value;
    const kondisi = document.getElementById('filter-kondisi').value;

    const params = new URLSearchParams({
        search: search,
        kondisi: kondisi
    });

    fetch(`{{ route('peminjam.kategori.show', $kategori->id) }}?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.text())
        .then(html => {
            document.getElementById('alat-container').innerHTML = html;
        });
}

document.getElementById('search').addEventListener('input', () => {
    clearTimeout(debounce);
    debounce = setTimeout(fetchAlat, 300);
});

document.getElementById('filter-kondisi').addEventListener('change', fetchAlat);
</script>
@endsection