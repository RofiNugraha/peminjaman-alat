<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\AlatController;
use App\Http\Controllers\Admin\DataSiswaController;
use App\Http\Controllers\Admin\DendaController as AdminDendaController;
use App\Http\Controllers\Admin\LogAktivitasController;
use App\Http\Controllers\Peminjam\KategoriAlatController;
use App\Http\Controllers\Admin\PeminjamanController as AdminPeminjamanController;
use App\Http\Controllers\Peminjam\DendaController as PeminjamDendaController;
use App\Http\Controllers\Peminjam\NotificationController;
use App\Http\Controllers\Petugas\PeminjamanController as PetugasPeminjamanController;
use App\Http\Controllers\Petugas\PengembalianController;
use App\Http\Controllers\Peminjam\PeminjamanController;
use App\Http\Controllers\Petugas\DendaController;
use App\Http\Controllers\Petugas\Laporan\DendaReportController;
use App\Http\Controllers\Petugas\Laporan\PeminjamanReportController;
use App\Http\Controllers\Petugas\Laporan\PengembalianReportController;
use App\Http\Controllers\ProfileController;
use App\Models\DataSiswa;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegister'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

Route::get('/forgot-password', [PasswordController::class, 'forgot'])->name('password.request');
Route::post('/send-otp', [PasswordController::class, 'sendOtp'])->name('send.otp');
Route::get('/verify-otp', [PasswordController::class, 'verifyOtpForm'])->name('password.verify');
Route::post('/verify-otp', [PasswordController::class, 'verifyOtp'])->name('otp.verify');
Route::get('/reset-password', [PasswordController::class, 'resetPasswordForm'])->name('password.reset.form');
Route::post('/reset-password', [PasswordController::class, 'resetPassword'])->name('password.update');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::put('/profile/siswa', [ProfileController::class, 'updateProfilSiswa'])->name('profile.siswa.update');
    Route::get('/get-siswa/{nisn}', function ($nisn) {return DataSiswa::where('nisn', $nisn)->first();
    });

    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::resource('users', UserController::class);
        
        Route::get('/data_siswa', [DataSiswaController::class, 'index'])->name('data_siswa.index');
        Route::post('/data_siswa/import', [DataSiswaController::class, 'import'])->name('data_siswa.import');
        
        Route::resource('kategori', KategoriController::class);
        
        Route::resource('alat', AlatController::class);
        
        Route::get('/peminjaman', [AdminPeminjamanController::class, 'index'])->name('admin.peminjaman.index');
        Route::get('/peminjanan/{peminjaman}', [AdminPeminjamanController::class, 'show'])->name('admin.peminjaman.show');
        
        Route::get('/log_aktivitas', [LogAktivitasController::class, 'index'])->name('admin.log_aktivitas.index');
        
        Route::get('/denda', [AdminDendaController::class, 'index'])->name('admin.denda.index');
        Route::get('/denda/{denda}', [AdminDendaController::class, 'show'])->name('admin.denda.show');
    });

    Route::middleware('role:petugas')->prefix('petugas')->name('petugas.')->group(function () {

        Route::get('/peminjaman', [PetugasPeminjamanController::class, 'index'])->name('peminjaman.index');
        Route::get('/peminjanan/{peminjaman}', [PetugasPeminjamanController::class, 'show'])->name('peminjaman.show');
        Route::post('/peminjaman/{peminjaman}/approve', [PetugasPeminjamanController::class, 'approve'])->name('peminjaman.approve');
        Route::post('/peminjaman/{peminjaman}/reject', [PetugasPeminjamanController::class, 'reject'])->name('peminjaman.reject');

        Route::get('/pengembalian', [PengembalianController::class, 'index'])->name('pengembalian.index');
        Route::get('/pengembalian/{pengembalian}', [PengembalianController::class, 'show'])->name('pengembalian.show');
        Route::post('/pengembalian/{peminjaman}', [PengembalianController::class, 'store'])->name('pengembalian.store');

        Route::get('/denda', [DendaController::class, 'index'])->name('denda.index');
        Route::get('/denda/{peminjaman}', [DendaController::class, 'show'])->name('denda.show');
        Route::post('/denda/{peminjaman}/ingatkan', [DendaController::class, 'ingatkan'])->name('denda.ingatkan');
        Route::post('/denda/{peminjaman}/lunas', [DendaController::class, 'lunas'])->name('denda.lunas');

        Route::prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/denda', [DendaReportController::class, 'index'])->name('denda.index');
            Route::get('/denda/pdf', [DendaReportController::class, 'exportPdf'])->name('denda.pdf');
            Route::get('/denda/excel', [DendaReportController::class, 'exportExcel'])->name('denda.excel');

            Route::get('/peminjaman', [PeminjamanReportController::class, 'index'])->name('peminjaman.index');
            Route::get('/peminjaman/pdf', [PeminjamanReportController::class, 'exportPdf'])->name('peminjaman.pdf');
            Route::get('/peminjaman/excel', [PeminjamanReportController::class, 'exportExcel'])->name('peminjaman.excel');

            Route::get('/pengembalian', [PengembalianReportController::class, 'index'])->name('pengembalian.index');
            Route::get('/pengembalian/pdf', [PengembalianReportController::class, 'exportPdf'])->name('pengembalian.pdf');
            Route::get('/pengembalian/excel', [PengembalianReportController::class, 'exportExcel'])->name('pengembalian.excel');
        });
    });

    Route::middleware('role:peminjam')->prefix('peminjam')->name('peminjam.')->group(function () {
        Route::get('/kategori-alat', [KategoriAlatController::class, 'index'])->name('kategori.index');
        Route::get('/kategori-alat/{kategori}', [KategoriAlatController::class, 'show'])->name('kategori.show');

        Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
        Route::get('/peminjaman/create/{alat}', [PeminjamanController::class, 'create'])->name('peminjaman.create');
        Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
        Route::get('/peminjaman/{peminjaman}', [PeminjamanController::class, 'show'])->name('peminjaman.show');
        Route::patch('/peminjaman/{peminjaman}/batal', [PeminjamanController::class, 'batal'])->name('peminjaman.batal');

        Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifikasi.index');
        Route::post('/notifikasi/{id}/baca', [NotificationController::class, 'markAsRead'])->name('notifikasi.baca');

        Route::get('/denda', [PeminjamDendaController::class, 'index'])->name('denda.index');
        Route::get('/denda/{peminjaman}', [PeminjamDendaController::class, 'show'])->name('denda.show');
    });
});