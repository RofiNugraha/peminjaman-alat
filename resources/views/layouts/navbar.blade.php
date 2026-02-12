<nav class="navbar navbar-expand-lg navbar-light bg-dark border-bottom fixed-top">
    <div class="container-fluid">
        <span class="navbar-brand fw-bold text-light">
            Peminjaman Alat
        </span>

        <div class="ms-auto d-flex align-items-center gap-3">
            @if(auth()->user()->role === 'peminjam')
            <li class="nav-item dropdown">
                <a class="nav-link position-relative text-white" href="{{ route('peminjam.notifikasi.index') }}">
                    <i class="bi bi-bell fs-5"></i>

                    @if(($unreadNotifCount ?? 0) > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ $unreadNotifCount > 9 ? '9+' : $unreadNotifCount }}
                    </span>
                    @endif
                </a>
            </li>
            @endif

            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button class="btn btn-outline-light btn-sm">
                    <i class="bi bi-box-arrow-right"></i>
                    Logout
                </button>
            </form>

        </div>
    </div>
</nav>