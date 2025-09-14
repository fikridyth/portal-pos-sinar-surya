<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\GiroController;
use App\Http\Controllers\HargaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KartuStokController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PiutangController;
use App\Http\Controllers\PpnController;
use App\Http\Controllers\PreOrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\QzTrayController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::name('auth.')->middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login-submit', [AuthController::class, 'loginSubmit'])->name('login-submit');
    Route::post('/register-submit', [AuthController::class, 'registerSubmit'])->name('register-submit');
});

Route::middleware('auth')->group(function () {
    Route::redirect('/', '/dashboard');
    Route::get('/dashboard', [HomeController::class, 'index'])->name('index');
    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');

    // scan barcode, print dan subtotal
    Route::get('/get-detail-products/{kode}', [HomeController::class, 'getDetailProducts']);
    Route::post('/penjualan/store', [HomeController::class, 'storePenjualan'])->name('penjualan.store');
    Route::post('/store-void-data', [HomeController::class, 'storeVoidData'])->name('store-void-data');

    // list pembelian dan print
    Route::get('/list-pembelian', [HomeController::class, 'listPembelian'])->name('list-pembelian');
    Route::post('/reprint-pembelian', [HomeController::class, 'ReprintPembelian'])->name('reprint-pembelian');
    Route::post('/reprint-pembelian-karton', [HomeController::class, 'ReprintPembelianKarton'])->name('reprint-pembelian-karton');

    // kirim data pembelian ke server
    Route::post('/send-penjualan', [ScheduleController::class, 'sendPenjualan'])->name('send-penjualan');

    // list supplier
    Route::get('/list-supplier', [HomeController::class, 'listSupplier'])->name('list-supplier');
    Route::get('/index-supplier/{id}', [HomeController::class, 'indexSupplier'])->name('index-supplier');
    Route::post('/store-return-data', [HomeController::class, 'storeReturnData'])->name('store-return-data');

    // list barang
    Route::get('/list-barang', [HomeController::class, 'listBarang'])->name('list-barang');
    Route::get('/list-barang-supplier/{id}', [HomeController::class, 'listBarangSupplier'])->name('list-barang-supplier');

    // list hold
    Route::post('/penjualan/hold', [HomeController::class, 'holdPenjualan'])->name('penjualan.hold');
    Route::get('/list-hold', [HomeController::class, 'listHold'])->name('list-hold');
    Route::get('/index-hold/{id}', [HomeController::class, 'indexHold'])->name('index-hold');

    // laporan kasir
    Route::get('/laporan-kasir', [HomeController::class, 'laporanKasir'])->name('laporan-kasir');
    Route::get('/index-laporan-kasir', [HomeController::class, 'indexLaporanKasir'])->name('index-laporan-kasir');

    // end of day
    Route::get('/end-of-day', [HomeController::class, 'endOfDay'])->name('end-of-day');
    Route::get('/index-end-of-day', [HomeController::class, 'indexEndOfDay'])->name('index-end-of-day');
    Route::post('/process-end-of-day', [ScheduleController::class, 'endOfDay'])->name('process-end-of-day');

    // qztray
    Route::post('/sign-message', [QzTrayController::class, 'signMessage']);
    Route::get('/cert.pem', [QzTrayController::class, 'getCertificate']);
});
