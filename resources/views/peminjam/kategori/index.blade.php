@extends('layouts.app')

@section('title', 'Pilih Kategori Alat')

@section('content')
<div id="mainContent" class="main-content">
    <div class="container-fluid px-4 py-4">
        <h4 class="fw-bold mb-4">Pilih Kategori Alat</h4>
        <form method="GET" action="{{ route('peminjam.kategori.index') }}" id="searchForm" class="mb-4">
            <div class="row g-2 align-items-center">
                <div class="col-md-4">
                    <input type="text" id="search" class="form-control mb-4" placeholder="Cari kategori alat..."
                        autocomplete="off">

                </div>
            </div>
        </form>
        <div id="kategori-container">
            @include('peminjam.kategori._list', ['kategoris' => $kategoris])
        </div>
    </div>
</div>

<script>
let timeout = null;

document.getElementById('search').addEventListener('keyup', function() {
    clearTimeout(timeout);

    let keyword = this.value;

    timeout = setTimeout(() => {
        fetch(`{{ route('peminjam.kategori.index') }}?search=${keyword}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.text())
            .then(html => {
                document.getElementById('kategori-container').innerHTML = html;
            });
    }, 400);
});
</script>

@endsection