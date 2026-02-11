@extends('layouts.app')

@section('title', 'Ajukan Peminjaman')

@section('content')
<div id="mainContent" class="main-content">
    <div class="container-fluid px-4 py-4">
        <h4 class="fw-bold mb-3">Ajukan Peminjaman Alat</h4>

        @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <img src="{{ asset('storage/'.$alat->gambar) }}" class="rounded" width="100"
                    alt="{{ $alat->nama_alat }}">
                <div class="mb-3">
                    <strong>Nama Alat:</strong> {{ $alat->nama_alat }} <br>
                    <strong>Stok Tersedia:</strong> {{ $alat->stok }} <br>
                    <strong>Harga Denda Per Hari Jika Telat Mengembalikan:</strong> Rp. {{ $alat->denda_per_hari }}
                </div>

                <form action="{{ route('peminjam.peminjaman.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_alat" value="{{ $alat->id }}">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Tanggal Pinjam</label>
                            <input type="date" name="tgl_pinjam" value="{{ old('tgl_pinjam') }}"
                                class="form-control @error('tgl_pinjam') is-invalid @enderror"
                                min="{{ now()->toDateString() }}" required>
                            @error('tgl_pinjam')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label>Tanggal Kembali</label>
                            <input type="date" name="tgl_kembali" value="{{ old('tgl_kembali') }}"
                                class="form-control @error('tgl_kembali') is-invalid @enderror" required>
                            @error('tgl_kembali')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Jumlah</label>
                        <input type="number" name="qty" value="{{ old('qty', 1) }}" min="1" max="{{ $alat->stok }}"
                            class="form-control @error('qty') is-invalid @enderror" required>
                        @error('qty')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="text-end">
                        <button class="btn btn-primary">Ajukan Peminjaman</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelector('[name="tgl_pinjam"]').addEventListener('change', function() {
    const kembali = document.querySelector('[name="tgl_kembali"]');
    kembali.min = this.value;
    kembali.value = '';
});
</script>

@endsection