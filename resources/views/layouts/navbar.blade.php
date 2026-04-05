<nav class="navbar navbar-expand-lg fixed-top navbar-modern">
    <div class="container-fluid">
        <button id="toggleSidebar" class="btn btn-light me-2">
            <i class="bi bi-list"></i>
        </button>

        <span class="navbar-brand fw-semibold">
            Sistem Peminjaman
        </span>

        <div class="ms-auto d-flex align-items-center gap-3">

            @if(auth()->user()->role === 'peminjam')
            <a class="nav-notif" href="{{ route('peminjam.notifikasi.index') }}">
                <span class="notif-icon">
                    <i class="bi bi-bell"></i>

                    @if(($unreadNotifCount ?? 0) > 0)
                    <span class="notif-badge">
                        {{ $unreadNotifCount > 9 ? '9+' : $unreadNotifCount }}
                    </span>
                    @endif
                </span>
            </a>
            @endif

            <button class="btn btn-outline-light btn-sm" onclick="document.getElementById('logout-form').submit()">
                Logout
            </button>

        </div>
    </div>
</nav>