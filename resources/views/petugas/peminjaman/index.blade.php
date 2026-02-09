@extends('layouts.app')

@section('title', 'Peminjaman Saya')

@section('content')
<div id="mainContent" class="main-content">
    <div class="container-fluid px-4 py-4">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Peminjam</th>
                    <th>Alat</th>
                    <th>Kategori</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($peminjamans as $p)
                <tr>
                    <td>{{ $p->user->nama }}</td>

                    <td>
                        @foreach($p->items as $item)
                        <div>{{ $item->alat->nama_alat }}</div>
                        @endforeach
                    </td>

                    <td>
                        @foreach($p->items as $item)
                        <div>{{ $item->alat->kategori->nama_kategori }}</div>
                        @endforeach
                    </td>

                    <td>{{ $p->tgl_pinjam }}</td>
                    <td>{{ $p->tgl_kembali }}</td>

                    <td class="d-flex gap-1">
                        <form action="{{ route('petugas.peminjaman.approve', $p->id) }}" method="POST">
                            @csrf
                            <button class="btn btn-success btn-sm" @disabled($p->status !== 'menunggu')>
                                Setujui
                            </button>
                        </form>

                        <form action="{{ route('petugas.peminjaman.reject', $p->id) }}" method="POST">
                            @csrf
                            <button class="btn btn-danger btn-sm" @disabled($p->status !== 'menunggu')>
                                Tolak
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection