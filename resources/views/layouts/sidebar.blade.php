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
        @endif

        @if(auth()->user()->role === 'petugas')
        <li class="nav-item">
            <a href="{{ route('petugas.peminjaman.index') }}"
                class="nav-link text-white {{ request()->routeIs('peminjam.kategori.*') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i>
                <span class="menu-text">Monitoring Peminjaman</span>
            </a>
        </li>
        @endif
    </ul>

    <div class="mt-auto px-3 py-3">
        <button class="btn btn-danger w-100" onclick="document.getElementById('logout-form').submit()">
            <i class="bi bi-box-arrow-right"></i>
            <span class="menu-text">Logout</span>
        </button>
    </div>
</aside>