@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div id="mainContent" class="main-content">
    <div class="container-fluid px-4 py-4">
        <h4 class="fw-bold mb-3">Notifikasi</h4>

        @if($notifications->isEmpty())
        <div class="alert alert-secondary text-center">
            Tidak ada notifikasi.
        </div>
        @else
        <div class="list-group shadow-sm">
            @foreach ($notifications as $notif)
            <div class="list-group-item d-flex justify-content-between align-items-start
                        {{ !$notif->dibaca ? 'list-group-item-warning' : '' }}">

                <div class="me-3">
                    <h6 class="fw-bold mb-1">
                        {{ $notif->judul }}
                        @if(!$notif->dibaca)
                        <span class="badge bg-danger ms-2">Baru</span>
                        @endif
                    </h6>

                    <p class="mb-1">
                        {{ $notif->pesan }}
                    </p>

                    <small class="text-muted">
                        {{ $notif->created_at->diffForHumans() }}
                    </small>
                </div>

                @if(!$notif->dibaca)
                <form action="{{ route('peminjam.notifikasi.baca', $notif->id) }}" method="POST">
                    @csrf
                    <button class="btn btn-sm btn-outline-primary">
                        Tandai Dibaca
                    </button>
                </form>
                @endif
            </div>
            @endforeach
        </div>

        <div class="mt-3">
            {{ $notifications->links('vendor.pagination.custom') }}
        </div>
        @endif
    </div>
</div>
@endsection