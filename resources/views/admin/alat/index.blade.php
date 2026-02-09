@extends('layouts.app')

@section('title', 'Data Alat')

@section('content')
<div id="mainContent" class="main-content">
    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0">Data Alat</h4>

            <div class="d-flex flex-wrap align-items-center gap-2">
                <form method="GET" class="d-flex gap-2">
                    <input type="text" id="search" class="form-control" placeholder="Cari nama alat...">

                    <select id="kategori" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                        @endforeach
                    </select>

                    <select id="kondisi" class="form-select">
                        <option value="">Semua Kondisi</option>
                        <option value="Baik">Baik</option>
                        <option value="Rusak">Rusak</option>
                        <option value="Hilang">Hilang</option>
                    </select>

                    <select id="order" class="form-select">
                        <option value="desc">Terbaru</option>
                        <option value="asc">Terlama</option>
                    </select>
                </form>

                <a href="{{ route('alat.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg"></i> Tambah Alat
                </a>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div id="table-data">
                    @include('admin.alat.partials.table')
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function loadData(page = 1) {
        $.ajax({
            url: "{{ route('alat.index') }}",
            type: "GET",
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            data: {
                search: $('#search').val(),
                kategori: $('#kategori').val(),
                kondisi: $('#kondisi').val(),
                order: $('#order').val(),
                per_page: $('#per_page').val(),
                page: page
            },
            success: function(html) {
                $('#table-data').html(html);
            }
        });
    }

    // SEARCH (debounced ringan)
    let typing;
    $(document).on('keyup', '#search', function() {
        clearTimeout(typing);
        typing = setTimeout(() => loadData(), 300);
    });

    $(document).on('change', '#kategori, #kondisi, #order, #per_page', function() {
        loadData();
    });

    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        const page = new URL(this.href).searchParams.get('page');
        loadData(page);
    });
</script>
@endpush
@endsection