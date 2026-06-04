<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MejaController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\DetailPesananController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\StrukController;

/*
|--------------------------------------------------------------------------
| DEFAULT
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])
    ->name('forgot.password');

Route::post('/forgot-password/cek', [AuthController::class, 'cekUsername'])
    ->name('forgot.password.cek');

Route::post('/forgot-password/proses', [AuthController::class, 'prosesReset'])
    ->name('forgot.password.proses');

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
        ->name('admin.dashboard');

    Route::resource('user', UserController::class);
    Route::resource('kategori', KategoriController::class);
    Route::resource('menu', MenuController::class);
    Route::resource('meja', MejaController::class);

    Route::get('/meja/status/{id}/{status}', [MejaController::class, 'updateStatus'])
        ->name('meja.status');

    /*
    |--------------------------------------------------------------------------
    | LAPORAN
    |--------------------------------------------------------------------------
    */
    Route::get('/laporan', [LaporanController::class, 'index'])
        ->name('laporan.index');

    // Fallback supaya refresh/back di /laporan/filter tidak error Method Not Allowed
    Route::get('/laporan/filter', function () {
        return redirect()->route('laporan.index');
    });

    Route::post('/laporan/filter', [LaporanController::class, 'filter'])
        ->name('laporan.filter');

    Route::get('/laporan/cetak/{tanggal}', [LaporanController::class, 'cetak'])
        ->name('laporan.cetak');

    Route::get('/laporan/menu-terlaris/cetak/{tanggal}', [LaporanController::class, 'cetakMenuTerlaris'])
        ->name('laporan.menu-terlaris.cetak');
});

/*
|--------------------------------------------------------------------------
| KASIR
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::get('/kasir/dashboard', [KasirController::class, 'dashboard'])
        ->name('kasir.dashboard');

    Route::get('/kasir/meja', [MejaController::class, 'indexKasir'])
        ->name('kasir.meja.index');

    /*
    |--------------------------------------------------------------------------
    | PESANAN
    |--------------------------------------------------------------------------
    */
    Route::get('/pesanan', [PesananController::class, 'index'])
        ->name('pesanan.index');

    Route::get('/pesanan/create', [PesananController::class, 'create'])
        ->name('pesanan.create');

    Route::post('/pesanan', [PesananController::class, 'store'])
        ->name('pesanan.store');

    Route::get('/pesanan/{id}/edit', [PesananController::class, 'edit'])
        ->name('pesanan.edit');

    Route::put('/pesanan/{id}', [PesananController::class, 'update'])
        ->name('pesanan.update');

    Route::get('/pesanan/{id}/detail', [PesananController::class, 'detail'])
        ->name('pesanan.detail');

    Route::get('/pesanan/{id}/bayar', [PesananController::class, 'showBayar'])
        ->name('pesanan.bayar');

    Route::post('/transaksi/{id}/proses', [PesananController::class, 'bayar'])
        ->name('transaksi.proses');

    /*
    |--------------------------------------------------------------------------
    | DAPUR / DETAIL PESANAN
    |--------------------------------------------------------------------------
    */
    Route::get('/dapur/{id}', [DetailPesananController::class, 'dapur'])
        ->name('dapur.index');

    Route::post('/dapur/{id}/selesai', [DetailPesananController::class, 'selesai'])
        ->name('dapur.selesai');

    // Cetak dapur dibuat GET supaya bisa dibuka setelah simpan pesanan
    Route::get('/dapur/{id}/cetak', [DetailPesananController::class, 'cetakDapur'])
        ->name('dapur.cetak');

    /*
    |--------------------------------------------------------------------------
    | TRANSAKSI
    |--------------------------------------------------------------------------
    */
    Route::get('/transaksi/{id}', [TransaksiController::class, 'show'])
        ->name('transaksi.show');

    Route::get('/transaksi/{id}/struk', [TransaksiController::class, 'struk'])
        ->name('transaksi.struk');

    /*
    |--------------------------------------------------------------------------
    | STRUK
    |--------------------------------------------------------------------------
    */
    Route::get('/struk/{id}', [StrukController::class, 'show'])
        ->name('struk.show');

    Route::get('/struk/{id}/cetak', [StrukController::class, 'cetak'])
        ->name('struk.cetak');

    Route::post('/struk/{id}/print', [StrukController::class, 'cetakThermal'])
        ->name('struk.print');
});
