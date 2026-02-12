<aside id="sidebar" class="sidebar bg-dark text-white">
    <div class="sidebar-header d-flex align-items-center justify-content-between px-3 py-3">
        <span class="sidebar-title fw-bold">{{ auth()->user()->role}}</span>
        <button id="toggleSidebar" class="btn btn-sm btn-outline-light">
            <i class="bi bi-list"></i>
        </button>
    </div>

    <ul class="nav flex-column px-2 mt-2">
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link text-white">
                <i class="bi bi-house"></i>
                <span class="menu-text">Dashboard</span>
            </a>
        </li>

        @if(auth()->user()->role === 'admin')
        <li class="nav-item">
            <a href="{{ url('/admin/users') }}" class="nav-link text-white">
                <i class="bi bi-people"></i>
                <span class="menu-text">Manajemen User</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('/admin/kategori') }}" class="nav-link text-white">
                <i class="bi bi-folder"></i>
                <span class="menu-text">Kategori</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('/admin/alat') }}" class="nav-link text-white">
                <i class="bi bi-tools"></i>
                <span class="menu-text">Data Alat</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.peminjaman.index') }}"
                class="nav-link text-white {{ request()->routeIs('admin.peminjaman.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i>
                <span class="menu-text">Monitoring Peminjaman</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.denda.index') }}"
                class="nav-link text-white {{ request()->routeIs('admin.denda*') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i>
                <span class="menu-text">Monitoring Denda</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.log_aktivitas.index') }}"
                class="nav-link text-white {{ request()->routeIs('admin.log_aktivitas.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i>
                <span class="menu-text">Log Aktivitas</span>
            </a>
        </li>
        @endif

        @if(auth()->user()->role === 'peminjam')
        <li class="nav-item">
            <a href="{{ route('peminjam.kategori.index') }}"
                class="nav-link text-white {{ request()->routeIs('peminjam.kategori.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i>
                <span class="menu-text">Pinjam Alat</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('peminjam.peminjaman.index') }}"
                class="nav-link text-white {{ request()->routeIs('peminjam.kategori.*') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i>
                <span class="menu-text">Monitoring Peminjaman</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('peminjam.denda.index') }}"
                class="nav-link text-white {{ request()->routeIs('peminjam.denda*') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i>
                <span class="menu-text">Denda Anda</span>
            </a>
        </li>
        @endif

        @if(auth()->user()->role === 'petugas')
        <li class="nav-item">
            <a href="{{ route('petugas.peminjaman.index') }}"
                class="nav-link text-white {{ request()->routeIs('petugas.peminjaman.*') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i>
                <span class="menu-text">Approval Peminjaman</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('petugas.pengembalian.index') }}"
                class="nav-link text-white {{ request()->routeIs('petugas.pengembalian*') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i>
                <span class="menu-text">Cek Pengembalian</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('petugas.denda.index') }}"
                class="nav-link text-white {{ request()->routeIs('petugas.denda*') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i>
                <span class="menu-text">Monitoring Denda</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('petugas.laporan.denda.index') }}"
                class="nav-link text-white {{ request()->routeIs('petugas.laporan.denda*') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i>
                <span class="menu-text">Laporan Denda</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('petugas.laporan.peminjaman.index') }}"
                class="nav-link text-white {{ request()->routeIs('petugas.laporan.peminjaman*') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i>
                <span class="menu-text">Laporan Peminjaman</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('petugas.laporan.pengembalian.index') }}"
                class="nav-link text-white {{ request()->routeIs('petugas.laporan.pengembalian*') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i>
                <span class="menu-text">Laporan Pengembalian</span>
            </a>
        </li>
        @endif

        <li class="nav-item">
            <a href="{{ route('profile.show') }}"
                class="nav-link text-white {{ request()->routeIs('profile*') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i>
                <span class="menu-text">Profile</span>
            </a>
        </li>
    </ul>

    <div class="mt-auto px-3 py-3">
        <button class="btn btn-danger w-100" onclick="document.getElementById('logout-form').submit()">
            <i class="bi bi-box-arrow-right"></i>
            <span class="menu-text">Logout</span>
        </button>
    </div>
</aside>