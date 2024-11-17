<?php

namespace Tests\Feature;
// namespace Tests\Unit;

use App\Http\Controllers\TransaksiController;
use App\Models\MethodePembayaran;
use App\Models\Product;
use App\Models\TemporaryImage;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TransaksiIntegrationTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();

        // Refresh database and run all seeders
        $this->artisan('db:seed');
    }
    public function test_succesfull_get_transaksi()
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
        // Data Transaksi
        // 'tanggal' => 'required|date|before_or_equal:today',
        // 'product' => 'required',
        // 'methode_pembayaran' => 'required',
        // 'jumlah' => 'required|numeric|min:1|regex:/^[1-9][0-9]*$/',
        // // 'total' => 'required',
        // 'is_complete' => 'required',
        $transaksiData = [
            'tanggal' => Carbon::now()->format('Y-m-d'),
            'product' => $productId, // Pastikan ini product_id
            'methode_pembayaran' => $methodePembayaran->id,
            'total' => $product->harga,
            'keterangan' => 'Test Keterangan',
            'jumlah' => 1,
            'is_complete' => 1
        ];

        // Menyimpan transaksi
        $response = $this->post(route('transaksis.store'), $transaksiData);
        // dd($response);
        $this->get(route('transaksis.index'))->assertOk();
        $this->get(route('transaksis.index'))->assertSee($product->total);
        $this->get(route('transaksis.index'))->assertSee($product->is_complete);


    }
    public function test_succesfull_get_transaksi_by_name()
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
        // Data Transaksi
        // 'tanggal' => 'required|date|before_or_equal:today',
        // 'product' => 'required',
        // 'methode_pembayaran' => 'required',
        // 'jumlah' => 'required|numeric|min:1|regex:/^[1-9][0-9]*$/',
        // // 'total' => 'required',
        // 'is_complete' => 'required',
        $transaksiData = [
            'tanggal' => Carbon::now()->format('Y-m-d'),
            'product' => $productId, // Pastikan ini product_id
            'methode_pembayaran' => $methodePembayaran->id,
            'total' => $product->harga,
            'keterangan' => 'Test Keterangan',
            'jumlah' => 1,
            'is_complete' => 1
        ];

        // Menyimpan transaksi
        $response = $this->post(route('transaksis.store'), $transaksiData);

        // $this->get(route('transaksis.index'))->assertOk();
        // $this->get(route('transaksis.index'))->assertSee($product->total);
        // $this->get(route('transaksis.index'))->assertSee($product->is_complete);

        // get by name
        // Search for the transaction by name
        $this->get('admin/transaksi?search=Test+Product')
            ->assertOk()
            ->assertSee($product->nama_product);

    }

    public function test_succesfull_get_transaksi_by_date()
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
            'keterangan' => 'Test Keterangan',
            'jumlah' => 1,
            'is_complete' => 1
        ];

        // Menyimpan transaksi
        $response = $this->post(route('transaksis.store'), $transaksiData);

        // $this->get(route('transaksis.index'))->assertOk();
// $this->get(route('transaksis.index'))->assertSee($product->total);
// $this->get(route('transaksis.index'))->assertSee($product->is_complete);

        // get by name
// Search for the transaction by name
        $this->get('admin/transaksi?search=' . Carbon::now()->format('Y-m-d'))
            ->assertOk()
            ->assertSee($product->nama_product);
    }
    public function test_user_can_show_detail_undone_transaksi()
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
            'keterangan' => 'Test Keterangan',
            'jumlah' => 1,
            'is_complete' => 0
        ];

        // Menyimpan transaksi
        $response = $this->post(route('transaksis.store'), $transaksiData);
        $transaksi = Transaksi::latest()->first();
        $transaksiId = $transaksi->id;

        // Ambil detail transaksi menggunakan ID yang valid
        $response = $this->get(route('transaksis.detail', ['transaksi' => $transaksiId]));

        // Pastikan respons berhasil dan memastikan is_complete = 0
        $this->assertDatabaseHas('transaksis', [
            'id' => $transaksiId,
            'is_complete' => 0,
        ]);



    }
    public function test_user_can_show_detail_done_transaksi()
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
            'keterangan' => 'Test Keterangan',
            'jumlah' => 1,
            'is_complete' => 1
        ];

        // Menyimpan transaksi
        $response = $this->post(route('transaksis.store'), $transaksiData);
        $transaksi = Transaksi::latest()->first();
        $transaksiId = $transaksi->id;

        // Ambil detail transaksi menggunakan ID yang valid
        $response = $this->get(route('transaksis.detail', ['transaksi' => $transaksiId]));

        // Pastikan respons berhasil dan memastikan is_complete = 0
        $this->assertDatabaseHas('transaksis', [
            'id' => $transaksiId,
            'is_complete' => 1,
        ]);


    }
    public function test_user_can_create_undone_transaksi()
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
            'keterangan' => 'Test Keterangan',
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
    public function test_user_can_create_done_transaksi()
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
            'keterangan' => 'Test Keterangan',
            'jumlah' => 1,
            'is_complete' => 1
        ];

        // Menyimpan transaksi
        $response = $this->post(route('transaksis.store'), $transaksiData);
        $transaksi = Transaksi::latest()->first();
        $transaksiId = $transaksi->id;
        // Pastikan respons berhasil dan memastikan is_complete = 0
        $this->assertDatabaseHas('transaksis', [
            'id' => $transaksiId,
            'is_complete' => 1,
        ]);
    }
    public function test_user_can_create_transaksi_with_transfer_method()
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
            'methode_pembayaran' => $methodePembayaranTransfer->id,
            'total' => $product->harga,
            'keterangan' => 'Test Keterangan',
            'jumlah' => 1,
            'is_complete' => 1
        ];

        // Menyimpan transaksi
        $response = $this->post(route('transaksis.store'), $transaksiData);
        $transaksi = Transaksi::latest()->first();
        $transaksiId = $transaksi->id;
        // Pastikan respons berhasil dan memastikan is_complete = 0
        $this->assertDatabaseHas('transaksis', [
            'id' => $transaksiId,
            'is_complete' => 1,
            'methode_pembayaran_id' => $methodePembayaranTransfer->id
        ]);
    }
    public function test_user_can_create_transaksi_with_shoppe_method()
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
            'methode_pembayaran' => $methodePembayaranShopee->id,
            'total' => $product->harga,
            'keterangan' => 'Test Keterangan',
            'jumlah' => 1,
            'is_complete' => 1
        ];

        // Menyimpan transaksi
        $response = $this->post(route('transaksis.store'), $transaksiData);
        $transaksi = Transaksi::latest()->first();
        $transaksiId = $transaksi->id;
        // Pastikan respons berhasil dan memastikan is_complete = 0
        $this->assertDatabaseHas('transaksis', [
            'id' => $transaksiId,
            'is_complete' => 1,
            'methode_pembayaran_id' => $methodePembayaranShopee->id
        ]);
    }
    public function test_user_can_create_transaksi_with_offline_method()
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
            'methode_pembayaran' => $methodePembayaranOffline->id,
            'total' => $product->harga,
            'keterangan' => 'Test Keterangan',
            'jumlah' => 1,
            'is_complete' => 1
        ];

        // Menyimpan transaksi
        $response = $this->post(route('transaksis.store'), $transaksiData);
        $transaksi = Transaksi::latest()->first();
        $transaksiId = $transaksi->id;
        // Pastikan respons berhasil dan memastikan is_complete = 0
        $this->assertDatabaseHas('transaksis', [
            'id' => $transaksiId,
            'is_complete' => 1,
            'methode_pembayaran_id' => $methodePembayaranOffline->id
        ]);
    }
    public function test_user_can_create_transaksi_with_other_method()
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
            'jumlah' => 1,
            'is_complete' => 1
        ];

        // Menyimpan transaksi
        $response = $this->post(route('transaksis.store'), $transaksiData);
        $transaksi = Transaksi::latest()->first();
        $transaksiId = $transaksi->id;
        // Pastikan respons berhasil dan memastikan is_complete = 0
        $this->assertDatabaseHas('transaksis', [
            'id' => $transaksiId,
            'is_complete' => 1,
            'methode_pembayaran_id' => $methodePembayaranLainnya->id
        ]);
    }
    public function test_user_cannot_edit_transaction_with_admin_role()
    {
        // Login
        $response = $this->post(route('authentication'), [
            'nama' => 'admin',
            'password' => 'admin',
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
            'is_complete' => 0
        ];

        // Menyimpan transaksi
        $response = $this->post(route('transaksis.store'), $transaksiData);
        $product->refresh();
        $transaksi = Transaksi::latest()->first();
        $transaksiId = $transaksi->id;

        $responseUpdate = $this->get(route('transaksis.edit', $transaksi->id)); // Ganti post dengan patch

        $responseUpdate->assertStatus(403);
    }
    public function test_user_cannot_update_transaction_with_admin_role()
    {
        // Login
        $response = $this->post(route('authentication'), [
            'nama' => 'admin',
            'password' => 'admin',
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
            'is_complete' => 0
        ];

        // Menyimpan transaksi
        $response = $this->post(route('transaksis.store'), $transaksiData);
        $product->refresh();
        $transaksi = Transaksi::latest()->first();
        $transaksiId = $transaksi->id;

        //update
        $transaksiUpdateData = [
            'product' => $productId,
            'total' => $transaksi->total_harga,
            'jumlah' => $transaksi->jumlah,
            'is_complete' => 1 // Mengubah status menjadi complete untuk memicu event dan log
        ];
        $responseUpdate = $this->patch(route('transaksis.update', $transaksi->id), $transaksiUpdateData); // Ganti post dengan patch

        $responseUpdate->assertStatus(403);
    }
    public function test_superadmin_owner_can_print_transaction()
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
            'is_complete' => 0
        ];

        // Menyimpan transaksi
        $response = $this->post(route('transaksis.store'), $transaksiData);
        $product->refresh();
        $transaksi = Transaksi::latest()->first();
        $transaksiId = $transaksi->id;
        $this->get(route('cetak.transaksi'))->assertStatus(200);

    }
    public function test_user_cannot_print_transaction_with_admin_role()
    {
        // Login
        $response = $this->post(route('authentication'), [
            'nama' => 'admin',
            'password' => 'admin',
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
            'is_complete' => 0
        ];

        // Menyimpan transaksi
        $response = $this->post(route('transaksis.store'), $transaksiData);
        $product->refresh();
        $transaksi = Transaksi::latest()->first();
        $transaksiId = $transaksi->id;
        $this->get(route('cetak.transaksi'))->assertStatus(403);
    }
    public function test_superadmin_owner_can_edit_undone_transaction_to_done()
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
            'total' => $product->harga,
            'keterangan' => 'Test Keterangan',
            'jumlah' => 1,
            'is_complete' => 0
        ];

        // Menyimpan transaksi
        $response = $this->post(route('transaksis.store'), $transaksiData);
        $transaksi = Transaksi::latest()->first();
        $product->refresh();
        // Update data transaksi
        $transaksiUpdateData = [
            'product' => $productId,
            'total' => $product->total_harga,
            'jumlah' => $product->jumlah,
            'is_complete' => 1 // Mengubah status menjadi complete untuk memicu event dan log
        ];

        $responseUpdate = $this->patch(route('transaksis.update', $transaksi->id), $transaksiUpdateData); // Ganti post dengan patch
        $product->refresh();
        // Pastikan respons berhasil dan memastikan is_complete = 0
        $this->assertDatabaseHas('transaksis', [
            'id' => $transaksi->id,
            'is_complete' => 1,
        ]);

    }

}
