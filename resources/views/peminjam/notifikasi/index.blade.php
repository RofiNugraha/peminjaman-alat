@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div class="page-header mb-4">
    <h3 class="mb-1">Notifikasi</h3>
    <p class="mb-0 text-muted">Informasi terbaru terkait aktivitas akun Anda</p>
</div>

@if($notifications->isEmpty())
<div class="card">
    <div class="card-body text-center py-5">
        <p class="text-muted mb-0">Belum ada notifikasi</p>
    </div>
</div>
@else

<div class="card">
    <div class="card-body p-0">

        <div class="list-group list-group-flush">

            @foreach ($notifications as $notif)
            <div class="list-group-item px-4 py-3 {{ !$notif->dibaca ? 'bg-light' : '' }}">

                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">

                    <div class="flex grow">

                        <div class="d-flex align-items-center gap-2 mb-1">
                            <h6 class="fw-semibold mb-0">
                                {{ $notif->judul }}
                            </h6>

                            @if(!$notif->dibaca)
                            <span class="badge bg-danger bg-opacity-10 text-danger">
                                Baru
                            </span>
                            @endif
                        </div>

                        <p class="text-muted mb-2">
                            {{ $notif->pesan }}
                        </p>

                        <small class="text-muted">
                            {{ $notif->created_at->diffForHumans() }}
                        </small>

                    </div>

                    <div class="d-flex flex-row flex-md-column gap-2">

                        @if($notif->url)
                        <a href="{{ $notif->url }}" class="btn btn-sm btn-primary">
                            Lihat Detail
                        </a>
                        @endif

                        @if(!$notif->dibaca)
                        <form action="{{ route('peminjam.notifikasi.baca', $notif->id) }}" method="POST">
                            @csrf
                            <button class="btn btn-sm btn-outline-primary w-100">
                                Tandai Dibaca
                            </button>
                        </form>
                        @endif

                    </div>

                </div>

            </div>
            @endforeach

        </div>

    </div>

    <div class="d-flex flex-wrap justify-content-between align-items-center p-3 border-top">

        <div class="small text-muted">
            Menampilkan {{ $notifications->firstItem() }} - {{ $notifications->lastItem() }} dari
            {{ $notifications->total() }} data
        </div>

        <div>
            {{ $notifications->links('vendor.pagination.custom') }}
        </div>

    </div>

</div>

@endif
@endsection