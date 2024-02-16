<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StoreUserController;
use App\Http\Controllers\TransaksiController;

Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->role == 'pelanggan') {
            return redirect()->route('order');
        } elseif ($user->role == 'petugas') {
            return redirect()->route('transaksi');
        } else {
            return redirect()->route('home');
        }
    }

    return redirect()->route('login');
});

Auth::routes();

Route::post('/storepelanggan', [StoreUserController::class, 'storePelanggan'])->name('store.pelanggan');

Route::middleware(['checkUserRole:admin'])->group(function () {
    Route::get('/user', [HomeController::class, 'home'])->name('home');
    Route::get('/createusers', [HomeController::class, 'createUsers'])->name('create.users');
    Route::post('/storeusers', [HomeController::class, 'storeUsers'])->name('store.users');
    Route::post('/deleteusers', [HomeController::class, 'deleteUsers'])->name('delete.users');
    Route::get('/editusers{id}', [HomeController::class, 'editUsers'])->name('edit.users');
    Route::post('/updateusers', [HomeController::class, 'updateUsers'])->name('update.users');
    Route::get('/kategori', [HomeController::class, 'kategori'])->name('kategori');
    Route::get('/createkategori', [HomeController::class, 'createKategori'])->name('create.kategori');
    Route::post('/storekategori', [HomeController::class, 'storeKategori'])->name('store.kategori');
    Route::post('/deletekategori', [HomeController::class, 'deleteKategori'])->name('delete.kategori');
    Route::get('/editkategori{id}', [HomeController::class, 'editKategori'])->name('edit.kategori');
    Route::post('/updatekategori', [HomeController::class, 'updateKategori'])->name('update.kategori');
    Route::get('/obat', [HomeController::class, 'obat'])->name('obat');
    Route::get('/obat/exp', [HomeController::class, 'expObat'])->name('exp.obat');
    Route::get('/createobat', [HomeController::class, 'createObat'])->name('create.obat');
    Route::post('/storeobat', [HomeController::class, 'storeObat'])->name('store.obat');
    Route::post('/deleteobat', [HomeController::class, 'deleteObat'])->name('delete.obat');
    Route::get('/editobat{id}', [HomeController::class, 'editObat'])->name('edit.obat');
    Route::post('/updateobat', [HomeController::class, 'updateObat'])->name('update.obat');
});

Route::middleware(['checkUserRole:pelanggan'])->group(function () {
    Route::get('/order', [OrderController::class, 'order'])->name('order');
    Route::post('/storeorder', [OrderController::class, 'storeOrder'])->name('store.order');
    Route::get('/keranjang', [OrderController::class, 'keranjang'])->name('keranjang');
    Route::put('/updateorder{id}', [OrderController::class, 'updateOrder'])->name('update.order');
    Route::delete('/deleteorder/{id}', [OrderController::class, 'DeleteOrder'])->name('delete.order');
    Route::post('/bayarpesanan', [OrderController::class, 'bayarPesanan'])->name('bayar.pesanan');
    Route::get('/riwayatorder', [OrderController::class, 'riwayatOrder'])->name('riwayat.order');
});

Route::middleware(['checkUserRole:admin,petugas'])->group(function () {
    Route::get('/transaksi', [TransaksiController::class, 'transaksi'])->name('transaksi');
    Route::post('/selesai', [TransaksiController::class, 'selesai'])->name('selesai');
    Route::get('/cetak-struk/{id_penjualan}', [TransaksiController::class, 'cetakStruk'])->name('cetak-struk');
});

Route::middleware(['checkUserRole:admin,petugas,pelanggan'])->group(function () {
    Route::get('/cetak-struk/{id_penjualan}', [TransaksiController::class, 'cetakStruk'])->name('cetak-struk');
});
