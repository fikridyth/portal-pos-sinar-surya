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

    // list pembelian dan print
    Route::get('/list-pembelian', [HomeController::class, 'listPembelian'])->name('list-pembelian');
    Route::post('/print-pembelian', [HomeController::class, 'printPembelian'])->name('print-pembelian');

    // kirim data pembelian ke server
    Route::post('/send-penjualan', [ScheduleController::class, 'sendPenjualan'])->name('send-penjualan');

    // list supplier
    Route::get('/list-supplier', [HomeController::class, 'listSupplier'])->name('list-supplier');
    Route::get('/index-supplier/{id}', [HomeController::class, 'indexSupplier'])->name('index-supplier');
    Route::post('/store-return-data', [HomeController::class, 'storeReturnData'])->name('store-return-data');

});
