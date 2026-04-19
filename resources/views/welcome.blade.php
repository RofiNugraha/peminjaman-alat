<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>{{ $settings->nama_aplikasi ?? 'Sipinjam' }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ICON -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar fixed-top">
        <div class="container d-flex align-items-center">

            <div class="d-flex align-items-center gap-2">
                <img src="{{ asset('storage/'.optional($settings)->logo_ungu) }}" width="36">
                <span class="fw-semibold">{{ optional($settings)->nama_aplikasi ?? 'Sipinjam' }}</span>
            </div>

            <div class="ms-auto d-flex align-items-center gap-2">
                @auth
                <a href="/dashboard" class="btn btn-primary btn-sm">Dashboard</a>
                @else
                <a href="{{ route('login') }}" class="btn btn-ghost btn-sm">Login</a>
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Daftar</a>
                @endauth
            </div>

        </div>
    </nav>

    <!-- HERO -->
    <section class="hero">
        <div class="container">
            <div class="row align-items-center">

                <!-- TEXT -->
                <div class="col-lg-6 text-center text-lg-start">
                    <h1 class="hero-title">
                        {{ optional($settings)->hero_title ?? 'Sistem Peminjaman Alat Sekolah Modern' }}
                    </h1>

                    <p class="hero-subtitle">
                        {{ optional($settings)->hero_subtitle ?? 'Kelola peminjaman alat dengan cepat, efisien, dan tanpa ribet.' }}
                    </p>

                    <div class="hero-buttons">
                        <a href="{{ route('login') }}" class="btn btn-primary">Mulai Sekarang</a>
                        <a href="#" class="btn btn-secondary">Lihat Demo</a>
                    </div>

                    <div class="hero-stats">
                        <div>
                            <h5>{{ $totalAlat }}+</h5>
                            <span>Alat</span>
                        </div>
                        <div>
                            <h5>{{ $totalUser }}+</h5>
                            <span>User</span>
                        </div>
                        <div>
                            <h5>100%</h5>
                            <span>Efisien</span>
                        </div>
                    </div>
                </div>

                <!-- LOGO WHITE -->
                <div class="col-lg-6 text-center mt-5 mt-lg-0">
                    <img src="{{ asset('storage/'.optional($settings)->logo_putih) }}" class="hero-logo">
                </div>

            </div>
        </div>
    </section>

    <!-- FEATURES -->
    <section class="section bg-white">
        <div class="container text-center">

            <h2 class="section-title">Kenapa Pilih Kami?</h2>
            <p class="section-subtitle">Solusi modern untuk pengelolaan alat sekolah</p>

            <div class="row mt-5">

                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="bi bi-lightning-charge"></i>
                        <h6>Cepat</h6>
                        <p>Proses peminjaman hanya dalam beberapa klik.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="bi bi-shield-lock"></i>
                        <h6>Aman</h6>
                        <p>Data tersimpan dengan aman.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="bi bi-graph-up"></i>
                        <h6>Terpantau</h6>
                        <p>Monitoring alat secara real-time.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- KATALOG -->
    <section class="section bg-light">
        <div class="container">

            <h2 class="section-title text-center">Alat Terbaru</h2>

            <div class="row mt-5">
                @forelse($alats as $alat)
                <div class="col-md-4">
                    <div class="product-card">
                        <img src="{{ asset('storage/'.$alat->gambar) }}">
                        <div class="p-3">
                            <h6>{{ $alat->nama_alat }}</h6>
                            <small>Stok: {{ $alat->stok }}</small>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-center text-muted">Belum ada alat tersedia</p>
                @endforelse
            </div>

        </div>
    </section>

    <!-- TESTIMONI -->
    <section class="section bg-white">
        <div class="container text-center">

            <h2 class="section-title">Testimoni</h2>

            <div class="row mt-5">
                @forelse($testimonials as $t)
                <div class="col-md-4 d-flex">
                    <div class="testimonial-card w-100">
                        <p class="testimonial-text">
                            "{{ $t->pesan }}"
                        </p>
                        <small>- {{ $t->nama }}</small>
                    </div>
                </div>
                @empty
                <p class="text-muted">Belum ada testimoni</p>
                @endforelse
            </div>

        </div>
    </section>

    <!-- CTA -->
    <section class="cta text-center">
        <div class="container">
            <h2>Mulai Sekarang</h2>
            <p>Kelola alat sekolah dengan lebih mudah</p>
            <a href="{{ route('register') }}" class="btn btn-light">Daftar Gratis</a>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="container">

            <div class="row text-center text-md-start">

                <!-- INFO SEKOLAH -->
                <div class="col-md-4 mb-4">
                    <h6 class="footer-title">
                        {{ optional($contact)->nama_sekolah ?? 'Nama Sekolah' }}
                    </h6>
                    <p class="footer-text">
                        {{ optional($contact)->alamat ?? 'Alamat belum tersedia' }}
                    </p>
                </div>

                <!-- KONTAK -->
                <div class="col-md-4 mb-4">
                    <h6 class="footer-title">Kontak</h6>

                    <p class="footer-text">
                        <i class="bi bi-envelope"></i>
                        {{ optional($contact)->email ?? '-' }}
                    </p>

                    <p class="footer-text">
                        <i class="bi bi-telephone"></i>
                        {{ optional($contact)->telepon ?? '-' }}
                    </p>
                </div>

                <!-- SOSIAL MEDIA -->
                <div class="col-md-4 mb-4">
                    <h6 class="footer-title">Sosial Media</h6>

                    @if(optional($contact)->instagram)
                    <p class="footer-text">
                        <i class="bi bi-instagram"></i>
                        <a href="https://instagram.com/{{ optional($contact)->instagram }}" target="_blank">
                            {{ optional($contact)->instagram }}
                        </a>
                    </p>
                    @endif

                    @if(optional($contact)->website)
                    <p class="footer-text">
                        <i class="bi bi-globe"></i>
                        <a href="{{ optional($contact)->website }}" target="_blank">
                            {{ optional($contact)->website }}
                        </a>
                    </p>
                    @endif
                </div>

            </div>

            <!-- COPYRIGHT -->
            <div class="text-center mt-4 footer-bottom">
                <small>© {{ date('Y') }} {{ optional($contact)->nama_sekolah ?? '' }}. All rights reserved.</small>
            </div>

        </div>
    </footer>

</body>

</html>