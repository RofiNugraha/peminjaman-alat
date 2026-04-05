<aside id="sidebar" class="sidebar">
    <div class="sidebar-header px-3 py-3">
        <span class="sidebar-title">
            {{ ucfirst(auth()->user()->role) }}
        </span>
    </div>

    <ul class="nav flex-column px-2">
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard*') ? 'active' : '' }}">
                <i class="bi bi-house"></i>
                <span class="menu-text">Dashboard</span>
            </a>
        </li>

        @if(auth()->user()->role === 'admin')
        @php
        $isMasterOpen = request()->is('admin/users*') ||
        request()->is('admin/data_siswa*') ||
        request()->is('admin/kategori*') ||
        request()->is('admin/alat*');
        @endphp

        <li class="nav-item">
            <a class="nav-link text-white d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
                href="#masterMenu" role="button" aria-expanded="{{ $isMasterOpen ? 'true' : 'false' }}"
                aria-controls="masterMenu">

                <span>
                    <i class="bi bi-folder2-open"></i>
                    <span class="menu-text ms-2">Master</span>
                </span>

                <i class="bi bi-chevron-down small"></i>
            </a>

            <div class="collapse {{ $isMasterOpen ? 'show' : '' }}" id="masterMenu">
                <ul class="nav flex-column ms-3 mt-2">

                    <li class="nav-item">
                        <a href="{{ url('/admin/users') }}"
                            class="nav-link text-white {{ request()->is('admin/users*') ? 'active' : '' }}">
                            <i class="bi bi-people"></i>
                            <span class="menu-text ms-2">Manajemen User</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('/admin/data_siswa') }}"
                            class="nav-link text-white {{ request()->is('admin/data_siswa*') ? 'active' : '' }}">
                            <i class="bi bi-person-badge"></i>
                            <span class="menu-text ms-2">Data Siswa</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('/admin/kategori') }}"
                            class="nav-link text-white {{ request()->is('admin/kategori*') ? 'active' : '' }}">
                            <i class="bi bi-folder"></i>
                            <span class="menu-text ms-2">Kategori</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ url('/admin/alat') }}"
                            class="nav-link text-white {{ request()->is('admin/alat*') ? 'active' : '' }}">
                            <i class="bi bi-tools"></i>
                            <span class="menu-text ms-2">Alat</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        @php
        $isMonitoringOpen = request()->is('admin/peminjaman*') ||
        request()->is('admin/denda*');
        @endphp

        <li class="nav-item">
            <a class="nav-link text-white d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
                href="#monitoringMenu" aria-expanded="{{ $isMonitoringOpen ? 'true' : 'false' }}">

                <span>
                    <i class="bi bi-bar-chart"></i>
                    <span class="menu-text ms-2">Monitoring</span>
                </span>

                <i class="bi bi-chevron-down small"></i>
            </a>

            <div class="collapse {{ $isMonitoringOpen ? 'show' : '' }}" id="monitoringMenu">
                <ul class="nav flex-column ms-3 mt-2">

                    <li class="nav-item">
                        <a href="{{ route('admin.peminjaman.index') }}"
                            class="nav-link text-white {{ request()->routeIs('admin.peminjaman.*') ? 'active' : '' }}">
                            <i class="bi bi-box-seam"></i>
                            <span class="menu-text ms-2">Peminjaman</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.denda.index') }}"
                            class="nav-link text-white {{ request()->routeIs('admin.denda*') ? 'active' : '' }}">
                            <i class="bi bi-cash-stack"></i>
                            <span class="menu-text ms-2">Denda</span>
                        </a>
                    </li>

                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a href="{{ route('admin.log_aktivitas.index') }}"
                class="nav-link text-white {{ request()->routeIs('admin.log_aktivitas.*') ? 'active' : '' }}">
                <i class="bi bi-activity"></i>
                <span class="menu-text ms-2">Log Aktivitas</span>
            </a>
        </li>
        @endif

        @if(auth()->user()->role === 'peminjam')
        @php
        $isAktivitasOpen = request()->is('peminjam/kategori*') ||
        request()->is('peminjam/peminjaman*') ||
        request()->is('peminjam/denda*');
        @endphp

        <li class="nav-item">
            <a class="nav-link text-white d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
                href="#aktivitasMenu" aria-expanded="{{ $isAktivitasOpen ? 'true' : 'false' }}">

                <span>
                    <i class="bi bi-grid"></i>
                    <span class="menu-text ms-2">Aktivitas</span>
                </span>

                <i class="bi bi-chevron-down small"></i>
            </a>

            <div class="collapse {{ $isAktivitasOpen ? 'show' : '' }}" id="aktivitasMenu">
                <ul class="nav flex-column ms-3 mt-2">

                    <li class="nav-item">
                        <a href="{{ route('peminjam.kategori.index') }}"
                            class="nav-link text-white {{ request()->routeIs('peminjam.kategori.*') ? 'active' : '' }}">
                            <i class="bi bi-box-seam"></i>
                            <span class="menu-text ms-2">Pinjam Alat</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('peminjam.peminjaman.index') }}"
                            class="nav-link text-white {{ request()->routeIs('peminjam.peminjaman.*') ? 'active' : '' }}">
                            <i class="bi bi-clock-history"></i>
                            <span class="menu-text ms-2">Monitoring Peminjaman</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('peminjam.denda.index') }}"
                            class="nav-link text-white {{ request()->routeIs('peminjam.denda*') ? 'active' : '' }}">
                            <i class="bi bi-cash-stack"></i>
                            <span class="menu-text ms-2">Denda Anda</span>
                        </a>
                    </li>

                </ul>
            </div>
        </li>
        @endif

        @if(auth()->user()->role === 'petugas')
        @php
        $isTransaksiOpen = request()->is('petugas/peminjaman*') ||
        request()->is('petugas/pengembalian*') ||
        request()->is('petugas/denda*');
        @endphp

        <li class="nav-item">
            <a class="nav-link text-white d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
                href="#transaksiMenu" aria-expanded="{{ $isTransaksiOpen ? 'true' : 'false' }}">

                <span>
                    <i class="bi bi-folder-check"></i>
                    <span class="menu-text ms-2">Transaksi</span>
                </span>

                <i class="bi bi-chevron-down small"></i>
            </a>

            <div class="collapse {{ $isTransaksiOpen ? 'show' : '' }}" id="transaksiMenu">
                <ul class="nav flex-column ms-3 mt-2">

                    <li class="nav-item">
                        <a href="{{ route('petugas.peminjaman.index') }}"
                            class="nav-link text-white {{ request()->routeIs('petugas.peminjaman.*') ? 'active' : '' }}">
                            <i class="bi bi-check2-circle"></i>
                            <span class="menu-text ms-2">Approval Peminjaman</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('petugas.pengembalian.index') }}"
                            class="nav-link text-white {{ request()->routeIs('petugas.pengembalian*') ? 'active' : '' }}">
                            <i class="bi bi-arrow-return-right"></i>
                            <span class="menu-text ms-2">Cek Pengembalian</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('petugas.denda.index') }}"
                            class="nav-link text-white {{ request()->routeIs('petugas.denda*') ? 'active' : '' }}">
                            <i class="bi bi-cash-stack"></i>
                            <span class="menu-text ms-2">Denda Peminjam</span>
                        </a>
                    </li>

                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a href="{{ route('petugas.laporan.denda.index') }}"
                class="nav-link text-white {{ request()->routeIs('petugas.laporan.denda*') ? 'active' : '' }}">
                <i class="bi bi-card-text"></i>
                <span class="menu-text">Laporan Denda</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('petugas.laporan.peminjaman.index') }}"
                class="nav-link text-white {{ request()->routeIs('petugas.laporan.peminjaman*') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-steps"></i>
                <span class="menu-text">Laporan Peminjaman</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('petugas.laporan.pengembalian.index') }}"
                class="nav-link text-white {{ request()->routeIs('petugas.laporan.pengembalian*') ? 'active' : '' }}">
                <i class="bi bi-calendar-range"></i>
                <span class="menu-text">Laporan Pengembalian</span>
            </a>
        </li>
        @endif

        <li class="nav-item">
            <a href="{{ route('profile.show') }}"
                class="nav-link text-white {{ request()->routeIs('profile*') ? 'active' : '' }}">
                <i class="bi bi-person"></i>
                <span class="menu-text">Profile</span>
            </a>
        </li>
    </ul>
</aside>