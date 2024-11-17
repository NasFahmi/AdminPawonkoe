<?php

namespace Tests\Unit;
use App\Models\MethodePembayaran;
use App\Models\Product;
use App\Models\TemporaryImage;
use App\Models\Transaksi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Tests\TestCase; // Change t
use Storage;

class DashboardTest extends TestCase
{
    /**
     * A basic unit test example.
     * !data di dashboard
     * ! 1. Total Transaksi
     * ! 2. Total Pendapatan
     * ! 3. Total Product Terjual
     * ! 4. total Preoder Selesai
     * ! 5. daftar product penjualan teratas
     * ! 6. Pesanan Preorder Terbaru (Belum Selesai)
     * ! 7. Produk Terbaru
     * ! 8. Produk Stok Kosong
     */
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();

        // Refresh database and run all seeders
        $this->artisan('db:seed');
    }
    public function test_view_data_total_transaksi_in_dashboard()
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

        $response->assertStatus(302);

        $this->get(route('admin.dashboard'))->assertSee('1');
    }
    public function test_view_data_total_pendapatan_in_dashboard()
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

        $response->assertStatus(302);

        $this->get(route('admin.dashboard'))->assertSee('100000');
    }

    public function test_view_data_total_product_terjual_in_dashboard()
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
            'total' => $product->harga * 2,
            'keterangan' => 'Test Keterangan',
            'jumlah' => 2,
            'is_complete' => 1
        ];

        // Menyimpan transaksi
        $response = $this->post(route('transaksis.store'), $transaksiData);

        $response->assertStatus(302);

        $this->get(route('admin.dashboard'))->assertSee('2');
    }
    public function test_view_data_total_preorder_selesai_in_dashboard()
    {
        // Log in the user
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        Storage::fake('public');

        // Create a temporary image
        $temporaryFolder = uniqid('image-', true);
        $temporaryImage = TemporaryImage::create([
            'folder' => $temporaryFolder,
            'file' => 'test-image.jpg'
        ]);

        Storage::disk('public')->put(
            "images/tmp/{$temporaryFolder}/test-image.jpg",
            UploadedFile::fake()->image('test-image.jpg')->size(100)
        );

        // Create a product
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

        // Retrieve the latest product ID
        $product = Product::latest()->first();
        $productId = $product->id;

        // Ensure that a payment method exists
        $methodePembayaran = MethodePembayaran::first() ?? MethodePembayaran::create([
            'methode_pembayaran' => 'Transfer'
        ]);

        // Prepare transaction data
        $transaksiData = [
            'tanggal' => now()->format('Y-m-d'),
            'jumlah' => 1,
            'total' => '100000',
            'nama' => 'John Doe',
            'email' => 'john.doe@example.com',
            'alamat' => '123 Street, City',
            'telepon' => '081234567890',
            'tanggal_dp' => now()->format('Y-m-d'),
            'jumlah_dp' => '50000',
            'product' => $product->id,
            'methode_pembayaran' => $methodePembayaran->id,
            'keterangan' => 'Test Keterangan'
        ];

        // Create a transaction
        $response = $this->post(route('preorders.store'), $transaksiData);
        $preorder = Transaksi::latest()->first();
        // dd($preorder);
        $preorderId = $preorder->id;

        // Assert that the response is a redirect and the transaction is in the database
        $response->assertStatus(302);

        // Prepare update data
        $updateData = [
            'tanggal' => now()->format('Y-m-d'),
            'jumlah' => 1,
            'total' => '100000',
            'nama' => 'John Doe',
            'email' => 'john.doe@example.com',
            'alamat' => '123 Street, City',
            'telepon' => '081234567890',
            'tanggal_dp' => now()->format('Y-m-d'),
            'jumlah_dp' => '50000',
            'product_id' => $productId,
            'methode_pembayaran' => $methodePembayaran->id,
            'keterangan' => 'Test Keterangan',
            'is_complete' => 1,
        ];

        // Update the transaction
        $response = $this->patch(route('preorders.update', $preorderId), $updateData);

        $this->get(route('admin.dashboard'))->assertSee('1');
    }
    public function test_view_data_daftar_product_penjualan_teratas_in_dashboard()
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
            'total' => $product->harga * 2,
            'keterangan' => 'Test Keterangan',
            'jumlah' => 2,
            'is_complete' => 1
        ];

        // Menyimpan transaksi
        $response = $this->post(route('transaksis.store'), $transaksiData);

        $response->assertStatus(302);

        $this->get(route('admin.dashboard'))->assertSee('Test Product');
    }
    public function test_view_data_pesanan_preorder_terbaru_in_dashboard()
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

        // Prepare the data for creating a transaction
        $transaksiData = [
            'tanggal' => now()->format('Y-m-d'),
            'jumlah' => 1,
            'total' => '100000',
            'nama' => 'John Doe',
            'email' => 'john.doe@example.com',
            'alamat' => '123 Street, City',
            'telepon' => '081234567890',
            'tanggal_dp' => now()->format('Y-m-d'),
            'jumlah_dp' => '50000',
            'product' => $product->id,
            'methode_pembayaran' => $methodePembayaran->id,
            'keterangan' => 'Test Keterangan'

        ];

        // Make the POST request to create a transaction
        $response = $this->post(route('preorders.store'), $transaksiData);

        // Assert that the response is a redirect
        $response->assertStatus(302);
        $this->get(route('admin.dashboard'))->assertSee('John Doe');

    }
    public function test_view_data_produk_terbaru_in_dashboard()
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

        
        $this->get(route('admin.dashboard'))->assertSee($product->nama_product);

    }
    public function test_view_data_produk_stok_kosong_in_dashboard()
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
            'total' => $product->harga * 10,
            'keterangan' => 'Test Keterangan',
            'jumlah' => 10,
            'is_complete' => 1
        ];

        // Menyimpan transaksi
        $response = $this->post(route('transaksis.store'), $transaksiData);

        $response->assertStatus(302);

        $this->get(route('admin.dashboard'))->assertSee('Test Product');
    }
}
