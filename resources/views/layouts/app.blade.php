<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    @auth
    @include('layouts.navbar')
    @include('layouts.sidebar')
    @endauth

    <main id="mainContent" class="main-content pt-5">
        <div class="container-fluid px-2 px-md-4 py-3">
            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    @if(session('success'))
    <script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: '{{ session("success") }}',
        timer: 1800,
        showConfirmButton: true
    });
    </script>
    @endif

    @if(session('error'))
    <script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: '{{ session("error") }}',
        timer: 1800,
        showConfirmButton: true
    });
    </script>
    @endif

    <script>
    function togglePassword() {
        const input = document.getElementById('password');
        const icon = document.getElementById('eyeIcon');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }

    function toggleSidebar() {
        document.body.classList.toggle('sidebar-collapsed');
    }

    document.getElementById('toggleSidebar')?.addEventListener('click', function() {
        const sidebar = document.getElementById('sidebar');
        const main = document.getElementById('mainContent');
        const body = document.body;

        if (window.innerWidth < 768) {
            sidebar.classList.toggle('show');
        } else {
            body.classList.toggle('sidebar-collapsed');
            main.classList.toggle('main-collapsed');

            if (body.classList.contains('sidebar-collapsed')) {
                document.querySelectorAll('#sidebar .collapse').forEach(el => {
                    el.classList.remove('show');
                });

                document.querySelectorAll('#sidebar [data-bs-toggle="collapse"]').forEach(el => {
                    el.setAttribute('aria-expanded', 'false');
                });
            }
        }
    });

    document.querySelectorAll('#sidebar [data-bs-toggle="collapse"]').forEach(el => {
        el.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');

            if (sidebar.classList.contains('sidebar-collapsed')) {
                e.preventDefault();
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {

        function confirmAction(buttonClass, title, text, confirmColor) {
            document.querySelectorAll(buttonClass).forEach(btn => {
                btn.addEventListener('click', function() {
                    const form = this.closest('form');

                    Swal.fire({
                        title: title,
                        text: text,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: confirmColor,
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        }

        confirmAction('.btn-approve', 'Setujui Peminjaman?', 'Pastikan data sudah benar', '#28a745');
        confirmAction('.btn-reject', 'Tolak Peminjaman?', 'Tindakan ini tidak bisa dibatalkan', '#dc3545');

    });
    </script>

    @stack('scripts')
</body>

</html>