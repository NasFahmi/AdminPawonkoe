<?php

namespace Tests\Unit;

use App\Http\Controllers\TransaksiController;
use App\Models\MethodePembayaran;
use App\Models\Product;
use App\Models\TemporaryImage;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Storage;
use Tests\TestCase; // Change this line to extend Laravel's TestCase

class TransaksiTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();

        // Refresh database and run all seeders
        $this->artisan('db:seed');
    }
    /**
        user berhasil show transaksi
        user dapat mencari transaksi berdasarkan nama product
        user dapat mencari transaksi berdasarkan nama tanggal
        user dapat melihat detail dari transaksi belum selesai 
        user dapat melihat detail dari transaksi selesai 
        user dapat membuat transaksi yang belum selesai
        user dapat membuat transaksi yang sudah selesai
        user dapat membuat transaksi menggunakan methode pembayaran transfer
        user dapat membuat transaksi menggunakan methode pembayaran shoppe
        user dapat membuat transaksi menggunakan methode pembayaran offline
        user dapat membuat transaksi menggunakan methode pembayaran lainnya
        gagal membuat transaksi ketika tanggal field kosong
        gagal membuat transaksi ketika jumlah field kosong
        gagal membuat transaksi ketika user menginputkan jumlah 0,atau -1
        gagal membuat transaksi ketika user menginputkan jumlah lebih dari stok
        gagal membuat transaksi ketika product stok kosong
        user dapat membaut transaksi tanpa menginputkan keterangan
        stok otomatis berkurang sesuai jumlah product yang diinputkan ketika create transaksi
        gagal ke halaman edit transaksi ketika login menggunaakn role admin
        gagal update transaksi ketika login menggunaakn role admin
        superadmin/owner dapat mengcetak transaksi
        gagal mengecetak transaksi ketika login menggunakan role admin
        superadmin/owder dapat mengedit status transaksi yang belum selesai ke selesai
        Total Harga Terhitung Otomatis Berdasarkan Jumlah Produk dan Harga Satuan
        Validasi Tanggal Transaksi Tidak Melebihi Tanggal Saat Ini
     */

    public function test_view_index_transaksi()
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        $response = $this->get(route('transaksis.index'));
        $response->assertStatus(200);
    }

    public function test_view_edit_transaksi()
    {

        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        Storage::fake('public');

        $temporaryFolder = uniqid('image-', true);
        $temporaryImage = TemporaryImage::create([
            'folder' => $temporaryFolder,
            'file' => 'test-image.jpg'
        ]);

        Storage::disk('public')->put(
            "images/tmp/{$temporaryFolder}/test-image.jpg",
            UploadedFile::fake()->image('test-image.jpg')->size(100)
        );

        $methodePembayaranTransfer = MethodePembayaran::create([
            'methode_pembayaran' => 'transfer'
        ]);
        $methodePembayaranShopee = MethodePembayaran::create([
            'methode_pembayaran' => 'shopee'
        ]);
        $methodePembayaranOffline = MethodePembayaran::create([
            'methode_pembayaran' => 'offline'
        ]);
        $methodePembayaranLainnya = MethodePembayaran::create([
            'methode_pembayaran' => 'lainnya'
        ]);

        // Create Product
        $productData = [
            'nama_product' => 'Test Product',
            'slug' => 'Test-Product',
            'harga' => '100000',
            'deskripsi' => 'Test Description',
            'link_shopee' => 'https://shopee.com/test',
            'stok' => '10',
            'tersedia' => '1',
            'spesifikasi_product' => 'Test Specifications',
            'images' => [json_encode([$temporaryFolder])],
            'varian' => ['Red', 'Blue']
        ];
        $product = Product::create($productData);
        $transaksi = Transaksi::create(
            [
                'tanggal' => Carbon::now(),
                'product_id' => $product->id,
                'methode_pembayaran_id' => $methodePembayaranTransfer->id,
                'jumlah' => 4,
                'total_harga' => 100000,
                'keterangan' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quod, recusandae.',
                'is_Preorder' => false,
                'Preorder_id' =>null,
                'is_complete' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
        $response = $this->get(route('transaksis.edit', $transaksi->id));
        $response->assertStatus(200);
    }
    public function test_view_create_transaksi()
    {

        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        $response = $this->get(route('transaksis.create'));
        $response->assertStatus(200);
    }
    public function test_view_cetak_transaksi()
    {

        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        $response = $this->get(route('cetak.transaksi'));
        $response->assertStatus(200);
    }
    public function test_user_cannot_create_transaksi_with_empty_date()
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        Storage::fake('public');

        $temporaryFolder = uniqid('image-', true);
        $temporaryImage = TemporaryImage::create([
            'folder' => $temporaryFolder,
            'file' => 'test-image.jpg'
        ]);

        Storage::disk('public')->put(
            "images/tmp/{$temporaryFolder}/test-image.jpg",
            UploadedFile::fake()->image('test-image.jpg')->size(100)
        );

        // Create Product
        $productData = [
            'nama_product' => 'Test Product',
            'slug' => 'Test-Product',
            'harga' => '100000',
            'deskripsi' => 'Test Description',
            'link_shopee' => 'https://shopee.com/test',
            'stok' => '10',
            'tersedia' => '1',
            'spesifikasi_product' => 'Test Specifications',
            'images' => [json_encode([$temporaryFolder])],
            'varian' => ['Red', 'Blue']
        ];

        $this->post(route('products.store'), $productData);

        // Ambil ID produk terakhir
        $product = Product::latest()->first();
        $productId = $product->id;
        // dd($productId);// 1 ->exsisting product

        // Pastikan metode pembayaran ada
        $methodePembayaranTransfer = MethodePembayaran::create([
            'methode_pembayaran' => 'transfer'
        ]);
        $methodePembayaranShopee = MethodePembayaran::create([
            'methode_pembayaran' => 'shopee'
        ]);
        $methodePembayaranOffline = MethodePembayaran::create([
            'methode_pembayaran' => 'offline'
        ]);
        $methodePembayaranLainnya = MethodePembayaran::create([
            'methode_pembayaran' => 'lainnya'
        ]);
        // dd($methodePembayaran); //transfer
        $transaksiData = [
            'tanggal' => null,
            'product' => $productId, // Pastikan ini product_id
            'methode_pembayaran' => $methodePembayaranLainnya->id,
            'total' => $product->harga,
            'keterangan' => 'Test Keterangan',
            'jumlah' => 1,
            'is_complete' => 1
        ];

        // Menyimpan transaksi
        $response = $this->post(route('transaksis.store'), $transaksiData);
        // dd($response);
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'tanggal' => 'The tanggal field is required.'
        ]);
    }
    public function test_user_cannot_create_transaksi_with_empty_quantity()
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        Storage::fake('public');

        $temporaryFolder = uniqid('image-', true);
        $temporaryImage = TemporaryImage::create([
            'folder' => $temporaryFolder,
            'file' => 'test-image.jpg'
        ]);

        Storage::disk('public')->put(
            "images/tmp/{$temporaryFolder}/test-image.jpg",
            UploadedFile::fake()->image('test-image.jpg')->size(100)
        );

        // Create Product
        $productData = [
            'nama_product' => 'Test Product',
            'slug' => 'Test-Product',
            'harga' => '100000',
            'deskripsi' => 'Test Description',
            'link_shopee' => 'https://shopee.com/test',
            'stok' => '10',
            'tersedia' => '1',
            'spesifikasi_product' => 'Test Specifications',
            'images' => [json_encode([$temporaryFolder])],
            'varian' => ['Red', 'Blue']
        ];

        $this->post(route('products.store'), $productData);

        // Ambil ID produk terakhir
        $product = Product::latest()->first();
        $productId = $product->id;
        // dd($productId);// 1 ->exsisting product

        // Pastikan metode pembayaran ada
        $methodePembayaranTransfer = MethodePembayaran::create([
            'methode_pembayaran' => 'transfer'
        ]);
        $methodePembayaranShopee = MethodePembayaran::create([
            'methode_pembayaran' => 'shopee'
        ]);
        $methodePembayaranOffline = MethodePembayaran::create([
            'methode_pembayaran' => 'offline'
        ]);
        $methodePembayaranLainnya = MethodePembayaran::create([
            'methode_pembayaran' => 'lainnya'
        ]);
        // dd($methodePembayaran); //transfer
        $transaksiData = [
            'tanggal' => Carbon::now()->format('Y-m-d'),
            'product' => $productId, // Pastikan ini product_id
            'methode_pembayaran' => $methodePembayaranLainnya->id,
            'total' => $product->harga,
            'keterangan' => 'Test Keterangan',
            'jumlah' => null,
            'is_complete' => 1
        ];

        // Menyimpan transaksi
        $response = $this->post(route('transaksis.store'), $transaksiData);
        // dd($response);
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'jumlah' => 'The jumlah field is required.'
        ]);
    }
    public function test_user_cannot_create_transaksi_with_quantity_less_than_1()
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        Storage::fake('public');

        $temporaryFolder = uniqid('image-', true);
        $temporaryImage = TemporaryImage::create([
            'folder' => $temporaryFolder,
            'file' => 'test-image.jpg'
        ]);

        Storage::disk('public')->put(
            "images/tmp/{$temporaryFolder}/test-image.jpg",
            UploadedFile::fake()->image('test-image.jpg')->size(100)
        );

        // Create Product
        $productData = [
            'nama_product' => 'Test Product',
            'slug' => 'Test-Product',
            'harga' => '100000',
            'deskripsi' => 'Test Description',
            'link_shopee' => 'https://shopee.com/test',
            'stok' => '10',
            'tersedia' => '1',
            'spesifikasi_product' => 'Test Specifications',
            'images' => [json_encode([$temporaryFolder])],
            'varian' => ['Red', 'Blue']
        ];

        $this->post(route('products.store'), $productData);

        // Ambil ID produk terakhir
        $product = Product::latest()->first();
        $productId = $product->id;
        // dd($productId);// 1 ->exsisting product

        // Pastikan metode pembayaran ada
        $methodePembayaranTransfer = MethodePembayaran::create([
            'methode_pembayaran' => 'transfer'
        ]);
        $methodePembayaranShopee = MethodePembayaran::create([
            'methode_pembayaran' => 'shopee'
        ]);
        $methodePembayaranOffline = MethodePembayaran::create([
            'methode_pembayaran' => 'offline'
        ]);
        $methodePembayaranLainnya = MethodePembayaran::create([
            'methode_pembayaran' => 'lainnya'
        ]);
        // dd($methodePembayaran); //transfer
        $transaksiData = [
            'tanggal' => Carbon::now()->format('Y-m-d'),
            'product' => $productId, // Pastikan ini product_id
            'methode_pembayaran' => $methodePembayaranLainnya->id,
            'total' => $product->harga,
            'keterangan' => 'Test Keterangan',
            'jumlah' => -1,
            'is_complete' => 1
        ];

        // Menyimpan transaksi
        $response = $this->post(route('transaksis.store'), $transaksiData);
        // dd($response);
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'jumlah' => 'The jumlah field must be at least 1.'
        ]);
    }
    public function test_user_cannot_create_transaksi_with_quantity_more_than_stock()
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        Storage::fake('public');

        $temporaryFolder = uniqid('image-', true);
        $temporaryImage = TemporaryImage::create([
            'folder' => $temporaryFolder,
            'file' => 'test-image.jpg'
        ]);

        Storage::disk('public')->put(
            "images/tmp/{$temporaryFolder}/test-image.jpg",
            UploadedFile::fake()->image('test-image.jpg')->size(100)
        );

        // Create Product
        $productData = [
            'nama_product' => 'Test Product',
            'slug' => 'Test-Product',
            'harga' => '100000',
            'deskripsi' => 'Test Description',
            'link_shopee' => 'https://shopee.com/test',
            'stok' => '10',
            'tersedia' => '1',
            'spesifikasi_product' => 'Test Specifications',
            'images' => [json_encode([$temporaryFolder])],
            'varian' => ['Red', 'Blue']
        ];

        $this->post(route('products.store'), $productData);

        // Ambil ID produk terakhir
        $product = Product::latest()->first();
        $productId = $product->id;
        // dd($productId);// 1 ->exsisting product

        // Pastikan metode pembayaran ada
        $methodePembayaran = MethodePembayaran::first();
        if (!$methodePembayaran) {
            $methodePembayaran = MethodePembayaran::create([
                'methode_pembayaran' => 'Transfer'
            ]);
        }
        // dd($methodePembayaran); //Transfer
        $transaksiData = [
            'tanggal' => Carbon::now()->format('Y-m-d'),
            'product' => $productId, // Pastikan ini product_id
            'methode_pembayaran' => $methodePembayaran->id,
            'total' => $product->harga * 11,
            'keterangan' => null,
            'jumlah' => 11,
            'is_complete' => 1
        ];

        // Menyimpan transaksi
        $response = $this->post(route('transaksis.store'), $transaksiData);
        $product->refresh();

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'jumlah' => 'Jumlah yang diminta melebihi stok yang tersedia.'
        ]);
    }
    public function test_user_cannot_create_transaksi_with_product_stock_empty()
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        Storage::fake('public');

        $temporaryFolder = uniqid('image-', true);
        $temporaryImage = TemporaryImage::create([
            'folder' => $temporaryFolder,
            'file' => 'test-image.jpg'
        ]);

        Storage::disk('public')->put(
            "images/tmp/{$temporaryFolder}/test-image.jpg",
            UploadedFile::fake()->image('test-image.jpg')->size(100)
        );

        // Create Product
        $productData = [
            'nama_product' => 'Test Product',
            'slug' => 'Test-Product',
            'harga' => '100000',
            'deskripsi' => 'Test Description',
            'link_shopee' => 'https://shopee.com/test',
            'stok' => '10',
            'tersedia' => '1',
            'spesifikasi_product' => 'Test Specifications',
            'images' => [json_encode([$temporaryFolder])],
            'varian' => ['Red', 'Blue']
        ];

        $this->post(route('products.store'), $productData);

        // Ambil ID produk terakhir
        $product = Product::latest()->first();
        $productId = $product->id;
        // dd($productId);// 1 ->exsisting product

        // Pastikan metode pembayaran ada
        $methodePembayaran = MethodePembayaran::first();
        if (!$methodePembayaran) {
            $methodePembayaran = MethodePembayaran::create([
                'methode_pembayaran' => 'Transfer'
            ]);
        }
        // dd($methodePembayaran); //Transfer
        $transaksiData = [
            'tanggal' => Carbon::now()->format('Y-m-d'),
            'product' => $productId, // Pastikan ini product_id
            'methode_pembayaran' => $methodePembayaran->id,
            'total' => $product->harga * 10,
            'keterangan' => null,
            'jumlah' => 10,
            'is_complete' => 1
        ];

        // Menyimpan transaksi
        $response = $this->post(route('transaksis.store'), $transaksiData);
        $product->refresh();

        // dd($methodePembayaran); //Transfer
        $transaksiData2 = [
            'tanggal' => Carbon::now()->format('Y-m-d'),
            'product' => $productId, // Pastikan ini product_id
            'methode_pembayaran' => $methodePembayaran->id,
            'total' => $product->harga * 1,
            'keterangan' => null,
            'jumlah' => 1,
            'is_complete' => 1
        ];

        // Menyimpan transaksi
        $responseTransaksi2 = $this->post(route('transaksis.store'), $transaksiData2);

        $responseTransaksi2->assertStatus(302);
        $responseTransaksi2->assertSessionHasErrors([
            'product' => 'Stok produk tidak tersedia.'
        ]);
    }
    public function test_user_cannot_create_transaksi_when_total_harga_calculated_not_same_with_request_total(){
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]); 
        Storage::fake('public');

        $temporaryFolder = uniqid('image-', true);
        $temporaryImage = TemporaryImage::create([
            'folder' => $temporaryFolder,
            'file' => 'test-image.jpg'
        ]);

        Storage::disk('public')->put(
            "images/tmp/{$temporaryFolder}/test-image.jpg",
            UploadedFile::fake()->image('test-image.jpg')->size(100)
        );

        $methodePembayaranTransfer = MethodePembayaran::create([
            'methode_pembayaran' => 'transfer'
        ]);
        $methodePembayaranShopee = MethodePembayaran::create([
            'methode_pembayaran' => 'shopee'
        ]);
        $methodePembayaranOffline = MethodePembayaran::create([
            'methode_pembayaran' => 'offline'
        ]);
        $methodePembayaranLainnya = MethodePembayaran::create([
            'methode_pembayaran' => 'lainnya'
        ]);

        // Create Product
        $productData = [
            'nama_product' => 'Test Product',
            'slug' => 'Test-Product',
            'harga' => '10000',
            'deskripsi' => 'Test Description',
            'link_shopee' => 'https://shopee.com/test',
            'stok' => '10',
            'tersedia' => '1',
            'spesifikasi_product' => 'Test Specifications',
            'images' => [json_encode([$temporaryFolder])],
            'varian' => ['Red', 'Blue']
        ];
        $product = Product::create($productData);
        $transaksiData = [
            'tanggal' => Carbon::now()->format('Y-m-d'),
            'product' => $product->id, // Pastikan ini product_id
            'methode_pembayaran' => $methodePembayaranTransfer->id,
            'total' => $product->harga * 3, //seharusnya ini dikali 2
            'keterangan' => null,
            'jumlah' => 2,
            'is_complete' => 1
        ];
        $totalHargafromRequest = $product->harga * $transaksiData['total'];

        // Menyimpan transaksi
        $response = $this->post(route('transaksis.store'), $transaksiData);
        $product->refresh();

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'total' => 'Total harga tidak cocok. Harap hitung ulang.'
        ]);

    }

    public function test_user_can_create_transaksi_without_keterangan()
    {
        // Login
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        Storage::fake('public');

        $temporaryFolder = uniqid('image-', true);
        $temporaryImage = TemporaryImage::create([
            'folder' => $temporaryFolder,
            'file' => 'test-image.jpg'
        ]);

        Storage::disk('public')->put(
            "images/tmp/{$temporaryFolder}/test-image.jpg",
            UploadedFile::fake()->image('test-image.jpg')->size(100)
        );

        // Create Product
        $productData = [
            'nama_product' => 'Test Product',
            'slug' => 'Test-Product',
            'harga' => '100000',
            'deskripsi' => 'Test Description',
            'link_shopee' => 'https://shopee.com/test',
            'stok' => '10',
            'tersedia' => '1',
            'spesifikasi_product' => 'Test Specifications',
            'images' => [json_encode([$temporaryFolder])],
            'varian' => ['Red', 'Blue']
        ];

        $this->post(route('products.store'), $productData);

        // Ambil ID produk terakhir
        $product = Product::latest()->first();
        $productId = $product->id;
        // dd($productId);// 1 ->exsisting product

        // Pastikan metode pembayaran ada
        $methodePembayaran = MethodePembayaran::first();
        if (!$methodePembayaran) {
            $methodePembayaran = MethodePembayaran::create([
                'methode_pembayaran' => 'Transfer'
            ]);
        }
        // dd($methodePembayaran); //Transfer
        $transaksiData = [
            'tanggal' => Carbon::now()->format('Y-m-d'),
            'product' => $productId, // Pastikan ini product_id
            'methode_pembayaran' => $methodePembayaran->id,
            'total' => $product->harga,
            'keterangan' => null,
            'jumlah' => 1,
            'is_complete' => 0
        ];

        // Menyimpan transaksi
        $response = $this->post(route('transaksis.store'), $transaksiData);
        $transaksi = Transaksi::latest()->first();
        $transaksiId = $transaksi->id;
        // Pastikan respons berhasil dan memastikan is_complete = 0
        $this->assertDatabaseHas('transaksis', [
            'id' => $transaksiId,
            'is_complete' => 0,
        ]);
    }
    public function test_stock_auto_decrease_after_create_transaction()
    {
        // Login
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        Storage::fake('public');

        $temporaryFolder = uniqid('image-', true);
        $temporaryImage = TemporaryImage::create([
            'folder' => $temporaryFolder,
            'file' => 'test-image.jpg'
        ]);

        Storage::disk('public')->put(
            "images/tmp/{$temporaryFolder}/test-image.jpg",
            UploadedFile::fake()->image('test-image.jpg')->size(100)
        );

        // Create Product
        $productData = [
            'nama_product' => 'Test Product',
            'slug' => 'Test-Product',
            'harga' => '100000',
            'deskripsi' => 'Test Description',
            'link_shopee' => 'https://shopee.com/test',
            'stok' => '10',
            'tersedia' => '1',
            'spesifikasi_product' => 'Test Specifications',
            'images' => [json_encode([$temporaryFolder])],
            'varian' => ['Red', 'Blue']
        ];

        $this->post(route('products.store'), $productData);

        // Ambil ID produk terakhir
        $product = Product::latest()->first();
        $productId = $product->id;
        // dd($productId);// 1 ->exsisting product

        // Pastikan metode pembayaran ada
        $methodePembayaran = MethodePembayaran::first();
        if (!$methodePembayaran) {
            $methodePembayaran = MethodePembayaran::create([
                'methode_pembayaran' => 'Transfer'
            ]);
        }
        // dd($methodePembayaran); //Transfer
        $transaksiData = [
            'tanggal' => Carbon::now()->format('Y-m-d'),
            'product' => $productId, // Pastikan ini product_id
            'methode_pembayaran' => $methodePembayaran->id,
            'total' => $product->harga * 2,
            'keterangan' => null,
            'jumlah' => 2,
            'is_complete' => 1
        ];

        // Menyimpan transaksi
        $response = $this->post(route('transaksis.store'), $transaksiData);
        $product->refresh();
        $transaksi = Transaksi::latest()->first();
        $transaksiId = $transaksi->id;
        // Pastikan respons berhasil dan memastikan is_complete = 0
        $this->assertDatabaseHas('transaksis', [
            'id' => $transaksiId,
            'is_complete' => 1,
        ]);
        // assert product->stok = 8
        $this->assertSame('8', $product->stok);
    }
    public function test_validate_total_harga_automatically()
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        Storage::fake('public');

        $temporaryFolder = uniqid('image-', true);
        $temporaryImage = TemporaryImage::create([
            'folder' => $temporaryFolder,
            'file' => 'test-image.jpg'
        ]);

        Storage::disk('public')->put(
            "images/tmp/{$temporaryFolder}/test-image.jpg",
            UploadedFile::fake()->image('test-image.jpg')->size(100)
        );

        // Create Product
        $productData = [
            'nama_product' => 'Test Product',
            'slug' => 'Test-Product',
            'harga' => '100000',
            'deskripsi' => 'Test Description',
            'link_shopee' => 'https://shopee.com/test',
            'stok' => '10',
            'tersedia' => '1',
            'spesifikasi_product' => 'Test Specifications',
            'images' => [json_encode([$temporaryFolder])],
            'varian' => ['Red', 'Blue']
        ];

        $this->post(route('products.store'), $productData);

        // Ambil ID produk terakhir
        $product = Product::latest()->first();
        $productId = $product->id;
        // dd($productId);// 1 ->exsisting product

        // Pastikan metode pembayaran ada
        $methodePembayaran = MethodePembayaran::first();
        if (!$methodePembayaran) {
            $methodePembayaran = MethodePembayaran::create([
                'methode_pembayaran' => 'Transfer'
            ]);
        }

        $transaksiData = [
            'tanggal' => Carbon::now()->format('Y-m-d'),
            'product' => $productId, // Pastikan ini product_id
            'methode_pembayaran' => $methodePembayaran->id,
            'total' => $product->harga * 3,
            'keterangan' => 'Test Keterangan',
            'jumlah' => 3,
            'is_complete' => 0
        ];

        // Menyimpan transaksi
        $response = $this->post(route('transaksis.store'), $transaksiData);
        $transaksi = Transaksi::latest()->first();
        $product->refresh();
        $this->assertSame($product->harga * 3, $transaksi->total_harga);

    }
    public function test_user_cannot_create_transaction_when_tanggal_transaksi_melebihi_tanggal_saat_ini()
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        Storage::fake('public');

        $temporaryFolder = uniqid('image-', true);
        $temporaryImage = TemporaryImage::create([
            'folder' => $temporaryFolder,
            'file' => 'test-image.jpg'
        ]);

        Storage::disk('public')->put(
            "images/tmp/{$temporaryFolder}/test-image.jpg",
            UploadedFile::fake()->image('test-image.jpg')->size(100)
        );

        // Create Product
        $productData = [
            'nama_product' => 'Test Product',
            'slug' => 'Test-Product',
            'harga' => '100000',
            'deskripsi' => 'Test Description',
            'link_shopee' => 'https://shopee.com/test',
            'stok' => '10',
            'tersedia' => '1',
            'spesifikasi_product' => 'Test Specifications',
            'images' => [json_encode([$temporaryFolder])],
            'varian' => ['Red', 'Blue']
        ];

        $this->post(route('products.store'), $productData);

        // Ambil ID produk terakhir
        $product = Product::latest()->first();
        $productId = $product->id;
        // dd($productId);// 1 ->exsisting product

        // Pastikan metode pembayaran ada
        $methodePembayaranTransfer = MethodePembayaran::create([
            'methode_pembayaran' => 'transfer'
        ]);
        $methodePembayaranShopee = MethodePembayaran::create([
            'methode_pembayaran' => 'shopee'
        ]);
        $methodePembayaranOffline = MethodePembayaran::create([
            'methode_pembayaran' => 'offline'
        ]);
        $methodePembayaranLainnya = MethodePembayaran::create([
            'methode_pembayaran' => 'lainnya'
        ]);
        // dd($methodePembayaran); //transfer
        $transaksiData = [
            'tanggal' => Carbon::now()->addDay()->format('Y-m-d'),
            'product' => $productId, // Pastikan ini product_id
            'methode_pembayaran' => $methodePembayaranLainnya->id,
            'total' => $product->harga,
            'keterangan' => 'Test Keterangan',
            'jumlah' => 1,
            'is_complete' => 1
        ];

        // Menyimpan transaksi
        $response = $this->post(route('transaksis.store'), $transaksiData);
        // dd($response);
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'tanggal' => 'The tanggal field must be a date before or equal to today.'
        ]);
    }
}
