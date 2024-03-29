<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PreorderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\Api\ApiTransaksiController;

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


Route::post('/login', [AuthController::class, 'Authlogin'])->name('authentication');
Route::get('/', [AuthController::class, 'loginview'])->name('login');


Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'indexDashboard'])->name('admin.dashboard');
    Route::get('/chart/oneyear', [DashboardController::class, 'chart'])->name('chart.1year');
    Route::get('/admin/dashboard/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/admin/transaksi/create', [TransaksiController::class, 'create'])
        ->middleware('role_or_permission:superadmin|tambah-product')
        ->name('transaksis.create');

    Route::get('/admin/product/create', [ProductController::class, 'create'])
        ->middleware('role_or_permission:superadmin|tambah-product')
        ->name('products.create');

    Route::get('/admin/preorder/create', [PreorderController::class, 'create'])
        ->middleware('role_or_permission:superadmin|tambah-preorder')
        ->name('preorders.create');
    //Transaksi
    
    Route::middleware(['permission:edit-transaksi|cetak-transaksi'])->group(function () {
        Route::get('/admin/transaksi/{transaksi}/edit', [TransaksiController::class, 'edit'])->name('transaksis.edit');
        Route::get('/admin/cetak/transaksi', [TransaksiController::class, 'cetakTransaksi'])->name('cetak.transaksi');
    });
    Route::get('/admin/transaksi', [TransaksiController::class, 'index'])->name('transaksis.index');
    Route::post('/admin/transaksi', [TransaksiController::class, 'store'])->name('transaksis.store');
    Route::get('/admin/transaksi/{transaksi}', [TransaksiController::class, 'show'])->name('transaksis.detail');
    Route::patch('/admin/transaksi/{transaksi}', [TransaksiController::class, 'update'])->name('transaksis.update');

    //Produk
    Route::middleware(['permission:edit-product|hapus-product'])->group(function () {
        Route::get('/admin/product/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::delete('/admin/product/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    });

    Route::get('/admin/product', [ProductController::class, 'index'])->name('products.index');
    Route::post('/admin/product', [ProductController::class, 'store'])->name('products.store');
    Route::get('/admin/product/{product}', [ProductController::class, 'show'])->name('products.detail');
    Route::patch('/admin/product/{product}', [ProductController::class, 'update'])->name('products.update');

    //Preorder
    Route::middleware(['permission:edit-preorder'])->group(function () {
        Route::get('/admin/preorder/{preorder}/edit', [PreorderController::class, 'edit'])->name('preorders.edit');
    });
    Route::get('/admin/preorder', [PreorderController::class, 'index'])->name('preorders.index');
    Route::post('/admin/preorder', [PreorderController::class, 'store'])->name('preorders.store');
    Route::get('/admin/preorder/{preorder}', [PreorderController::class, 'show'])->name('preorders.detail');
    Route::patch('/admin/preorder/{preorder}', [PreorderController::class, 'update'])->name('preorders.update');



});










// Route::group(['middleware' => ['role:superadmin']], function () {
//     Route::get('/admin/transksi/{id}/edit',[TransaksiController::class,'edit']);
//     //
// });