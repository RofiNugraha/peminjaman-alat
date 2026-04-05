@extends('layouts.app')

@section('title', 'Data Alat')

@section('content')
<div class="page-header mb-4">
    <h3 class="mb-1">Data Alat</h3>
    <p class="mb-0 text-muted">Kelola data alat secara terpusat</p>
</div>

<div class="card mb-3">
    <div class="card-body d-flex flex-wrap justify-content-between align-items-end gap-3">

        <form onsubmit="return false;" class="d-flex flex-wrap gap-2 align-items-end">

            <div>
                <label class="form-label small">Cari</label>
                <input type="text" id="search" class="form-control" placeholder="Nama alat">
            </div>

            <div>
                <label class="form-label small">Kategori</label>
                <select id="kategori" class="form-select">
                    <option value="">Semua</option>
                    @foreach($kategoris as $kategori)
                    <option value="{{ $kategori->id }}">
                        {{ $kategori->nama_kategori }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="form-label small">Urutan</label>
                <select id="order" class="form-select">
                    <option value="desc">Terbaru</option>
                    <option value="asc">Terlama</option>
                </select>
            </div>

        </form>

        <a href="{{ route('alat.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i>
            Tambah Alat
        </a>

    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div id="table-data">
            @include('admin.alat.partials.table')
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
            order: $('#order').val(),
            per_page: $('#per_page').val(),
            page: page
        },
        success: function(html) {
            $('#table-data').html(html);
        }
    });
}

let typing;
$(document).on('keyup', '#search', function() {
    clearTimeout(typing);
    typing = setTimeout(() => loadData(), 300);
});

$(document).on('change', '#kategori, #order, #per_page', function() {
    loadData();
});

$(document).on('click', '.pagination a', function(e) {
    e.preventDefault();
    const page = new URL(this.href).searchParams.get('page');
    loadData(page);
});

$('.delete-form').submit(function(e) {

    e.preventDefault();

    Swal.fire({
        title: 'Hapus alat?',
        text: 'Data tidak dapat dikembalikan',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Hapus'
    }).then((result) => {

        if (result.isConfirmed) {
            this.submit();
        }

    });

});
</script>
@endpush
@endsection