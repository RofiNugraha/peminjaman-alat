<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\AlatController;
use App\Http\Controllers\Peminjam\KategoriAlatController;
use App\Http\Controllers\Petugas\PeminjamanController as PetugasPeminjamanController;
use App\Http\Controllers\Petugas\PengembalianController;
use App\Http\Controllers\Peminjam\PeminjamanController;

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
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('kategori', KategoriController::class);
        Route::resource('alat', AlatController::class);
    });

    Route::middleware('role:petugas')->prefix('petugas')->name('petugas.')->group(function () {
        
        Route::get('/peminjaman', [PetugasPeminjamanController::class, 'index'])->name('peminjaman.index');
        Route::post('/peminjaman/{peminjaman}/approve', [PetugasPeminjamanController::class, 'approve'])->name('peminjaman.approve');
        Route::post('/peminjaman/{peminjaman}/reject', [PetugasPeminjamanController::class, 'reject'])->name('peminjaman.reject');
        
        Route::resource('pengembalian', PengembalianController::class);
    });

    Route::middleware('role:peminjam')->prefix('peminjam')->name('peminjam.')->group(function () {
        Route::get('/kategori-alat', [KategoriAlatController::class, 'index'])->name('kategori.index');
        Route::get('/kategori-alat/{kategori}', [KategoriAlatController::class, 'show'])->name('kategori.show');

        Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
        Route::get('/peminjaman/create/{alat}',[PeminjamanController::class, 'create'])->name('peminjaman.create');
        Route::post('/peminjaman',[PeminjamanController::class, 'store'])->name('peminjaman.store');
        Route::patch('/peminjaman/{peminjaman}/batal', [PeminjamanController::class, 'batal'])->name('peminjaman.batal');
    });
});