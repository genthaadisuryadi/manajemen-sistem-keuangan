<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RegisterController;

Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login/proses', [AuthController::class, 'proses'])->name('login.proses');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index']);

/*
|--------------------------------------------------------------------------
| BANK
|--------------------------------------------------------------------------
*/
Route::get('/bank', [BankController::class, 'index'])->name('bank.index');
Route::post('/bank/act', [BankController::class, 'store'])->name('bank.act');
Route::get('/bank/hapus/{id}', [BankController::class, 'hapus'])->name('bank.hapus');
Route::post('/bank/update', [BankController::class, 'update'])->name('bank.update');

/*
|--------------------------------------------------------------------------
| KATEGORI
|--------------------------------------------------------------------------
*/
Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
Route::post('/kategori/act', [KategoriController::class, 'kategori_act'])->name('kategori.act');
Route::get('/kategori/hapus/{id}', [KategoriController::class, 'kategori_hapus'])->name('kategori.hapus');
Route::post('/kategori/update', [KategoriController::class, 'kategori_update'])->name('kategori.update');

/*
|--------------------------------------------------------------------------
| MAHASISWA
|--------------------------------------------------------------------------
*/
Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa.index');
Route::post('/mahasiswa/act', [MahasiswaController::class, 'mahasiswa_act'])->name('mahasiswa.act');
Route::post('/mahasiswa/bayar', [MahasiswaController::class, 'mahasiswa_bayar'])->name('mahasiswa.bayar');
Route::get('/mahasiswa/hapus/{id}', [MahasiswaController::class, 'mahasiswa_hapus'])->name('mahasiswa.hapus');
Route::post('/mahasiswa/update', [MahasiswaController::class, 'mahasiswa_update'])->name('mahasiswa.update');

/*
|--------------------------------------------------------------------------
| TRANSAKSI
|--------------------------------------------------------------------------
*/
Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
Route::post('/transaksi/act', [TransaksiController::class, 'transaksi_act'])->name('transaksi.act');
Route::get('/transaksi/bca', [TransaksiController::class, 'transaksi_bca'])->name('transaksi.bca');
Route::get('/transaksi/bsi', [TransaksiController::class, 'transaksi_bsi'])->name('transaksi.bsi');
Route::get('/transaksi/cash', [TransaksiController::class, 'transaksi_cash'])->name('transaksi.cash');
Route::get('/transaksi/hapus/{id}', [TransaksiController::class, 'transaksi_hapus'])->name('transaksi.hapus');
Route::post('/transaksi/update', [TransaksiController::class, 'transaksi_update'])->name('transaksi.update');

/*
|--------------------------------------------------------------------------
| LAPORAN
|--------------------------------------------------------------------------
*/
Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
Route::get('/laporan/print', [LaporanController::class, 'laporan_print'])->name('laporan.print');
Route::get('/laporan/pdf', [LaporanController::class, 'laporan_pdf'])->name('laporan.pdf');
Route::get('/laporan/backup', [LaporanController::class, 'laporan_backup'])->name('laporan.backup');

/*
|--------------------------------------------------------------------------
| USER
|--------------------------------------------------------------------------
*/
Route::get('/user', [UserController::class, 'index'])->name('user.index');
Route::get('/user/tambah', [UserController::class, 'tambah'])->name('user.tambah');
Route::post('/user/act', [UserController::class, 'store'])->name('user.act');
Route::get('/user/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
Route::post('/user/update', [UserController::class, 'update'])->name('user.update');
Route::get('/user/hapus/{id}', [UserController::class, 'hapus'])->name('user.hapus');
Route::get('/profile', [UserController::class, 'profile'])
        ->name('user.profile');

    Route::post('/password', [UserController::class, 'updatePassword'])
        ->name('user.password');

