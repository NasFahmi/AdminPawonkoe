<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PiutangController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PreorderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\BebanKewajibanController;
use App\Http\Controllers\TemporaryImageController;
use App\Http\Controllers\Api\ApiTransaksiController;
use App\Http\Controllers\ProduksiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
/ role property

        $admin = Role::findByName('admin');
        $admin->givePermissionTo('tambah-product');
        $admin->givePermissionTo('tambah-transaksi');
        $admin->givePermissionTo('tambah-preorder');

        $superAdmin = Role::findByName('superadmin');
        $superAdmin->givePermissionTo('edit-preorder');
        $superAdmin->givePermissionTo('edit-transaksi');
        $superAdmin->givePermissionTo('edit-product');
        $superAdmin->givePermissionTo('hapus-product');
        $superAdmin->givePermissionTo('cetak-transaksi');
|
*/


Route::post('/login', [AuthController::class, 'Authlogin'])->name('authentication');
Route::get('/', [AuthController::class, 'loginview'])->name('login');

Route::middleware(['auth'])->group(function () {
    Route::middleware(['role:superadmin|admin'])->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'indexDashboard'])->name('admin.dashboard');
        Route::get('/chart/oneyear', [DashboardController::class, 'chart'])->name('chart.1year');
        Route::get('/admin/dashboard/logout', [AuthController::class, 'logout'])->name('logout');
    });

    //Transaksi 
    Route::middleware(['role:superadmin', 'permission:edit-transaksi|cetak-transaksi'])->group(function () {
        Route::get('/admin/transaksi/{transaksi}/edit', [TransaksiController::class, 'edit'])->name('transaksis.edit');
        Route::get('/admin/cetak/transaksi', [TransaksiController::class, 'cetakTransaksi'])->name('cetak.transaksi');
        Route::patch('/admin/transaksi/{transaksi}', [TransaksiController::class, 'update'])->name('transaksis.update');
    });
    Route::middleware(['role:superadmin|admin', 'permission:tambah-transaksi'])->group(function () {
        Route::post('/admin/transaksi', [TransaksiController::class, 'store'])->name('transaksis.store');
        Route::get('/admin/transaksi/create', [TransaksiController::class, 'create'])->name('transaksis.create');
    });
    Route::middleware(['role:superadmin|admin'])->group(function () {
        Route::get('/admin/transaksi', [TransaksiController::class, 'index'])->name('transaksis.index');
        Route::get('/admin/transaksi/{transaksi}', [TransaksiController::class, 'show'])->name('transaksis.detail');
    });

    //Produk
    //middleware umum tanpa permission
    Route::middleware(['role:superadmin|admin'])->group(function () {
        Route::get('/admin/product', [ProductController::class, 'index'])->name('products.index');
        Route::get('/admin/product/{product}', [ProductController::class, 'show'])->name('products.detail');


        Route::post('/product/upload', [TemporaryImageController::class, 'uploadTemporary'])->name('upload.temporary');
        Route::post('/product/revert', [TemporaryImageController::class, 'deleteTemporary'])->name('delete.temporary');
        Route::post('/product/update-image/{id}', [TemporaryImageController::class, 'uploadImageDirectlyToDB'])->name('upload.directtoDB');
    });
    // dengan permission
    Route::middleware(['role:superadmin', 'permission:edit-product|hapus-product'])->group(function () {
        Route::get('/admin/product/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::delete('/admin/product/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::patch('/admin/product/{product}', [ProductController::class, 'update'])->name('products.update');
    });
    //dengan permmission
    Route::middleware(['role:superadmin|admin', 'permission:tambah-product'])->group(function () {
        Route::post('/admin/product', [ProductController::class, 'store'])->name('products.store');

        Route::get('/admin/create/product', [ProductController::class, 'create'])->name('products.create'); //works
    });



    //Preorder
    Route::middleware(['role:superadmin', 'permission:edit-preorder'])->group(function () {
        Route::get('/admin/preorder/{preorder}/edit', [PreorderController::class, 'edit'])->name('preorders.edit');
        Route::patch('/admin/preorder/{preorder}', [PreorderController::class, 'update'])->name('preorders.update');
    });
    Route::middleware(['role:superadmin|admin', 'permission:tambah-preorder'])->group(function () {
        Route::get('/preorder/create', [PreorderController::class, 'create'])->name('preorders.create');
        Route::post('/preorder', [PreorderController::class, 'store'])->name('preorders.store');
    });
    Route::middleware(['role:superadmin|admin'])->group(function () {
        Route::get('/admin/preorder', [PreorderController::class, 'index'])->name('preorders.index');
        Route::get('/admin/preorder/{preorder}', [PreorderController::class, 'show'])->name('preorders.detail');
    });

    //produksi
    Route::middleware(['role:superadmin||admin'])->group(function () {
        Route::post('/produksi', [ProduksiController::class, 'store'])->name('produksi.store');
        Route::get('/produksi/create', [ProduksiController::class, 'create'])->name('produksi.create');
        Route::get('/admin/produksi', [ProduksiController::class, 'index'])->name('produksi.index');
        Route::patch('/admin/produksi/{produksi}', [ProduksiController::class, 'update'])->name('produksi.update');
        Route::get('/admin/produksi/{produksi}/edit', [ProduksiController::class, 'edit'])->name('produksi.edit');
        Route::delete('/admin/produksi/{produksi}', [ProduksiController::class, 'destroy'])->name('produksi.destroy');
    });

    //Beban Kewajiban
    Route::middleware(['role:superadmin'])->group(function () {
        Route::post('/beban-kewajiban', [BebanKewajibanController::class, 'store'])->name('beban-kewajibans.store');
        Route::get('/beban-kewajiban/create', [BebanKewajibanController::class, 'create'])->name('beban-kewajibans.create');
        Route::get('/admin/beban-kewajiban', [BebanKewajibanController::class, 'index'])->name('beban-kewajibans.index');
        Route::patch('/admin/beban-kewajiban/{bebanKewajiban}', [BebanKewajibanController::class, 'update'])->name('beban-kewajibans.update');
        Route::get('/admin/beban-kewajiban/{bebanKewajiban}/edit', [BebanKewajibanController::class, 'edit'])->name('beban-kewajibans.edit');
        Route::delete('/admin/beban-kewajiban/{bebanKewajiban}', [BebanKewajibanController::class, 'destroy'])->name('beban-kewajibans.destroy');
    });

    //Piutang
    Route::middleware(['role:superadmin||admin'])->group(function () {
        Route::get('/admin/piutang', [PiutangController::class, 'index'])->name('piutang.index');
        Route::get('/admin/piutang/create', [PiutangController::class, 'create'])->name('piutang.create');
        Route::post('/admin/piutang', [PiutangController::class, 'store'])->name('piutang.store');
        Route::get('/admin/piutang/{piutang}/edit', [PiutangController::class, 'edit'])->name('piutang.edit');
        Route::patch('/admin/piutang/{piutang}', [PiutangController::class, 'update'])->name('piutang.update');
        Route::delete('/admin/piutang/{piutang}', [PiutangController::class, 'destroy'])->name('piutang.destroy');
    });
});










// Route::group(['middleware' => ['role:superadmin']], function () {
//     Route::get('/admin/transksi/{id}/edit',[TransaksiController::class,'edit']);
//     //
// });