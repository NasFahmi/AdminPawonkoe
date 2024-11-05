<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Storage;
use Tests\TestCase; // Change t
use App\Models\MethodePembayaran;
use App\Models\Product;
use App\Models\TemporaryImage;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;

class PreorderTest extends TestCase
{
    /**
     user berhasil show semua data preorder
    user dapat mencari preroder berdasarkan nama product
    user dapat mencari preoder berdasarkan nama tanggal
    user dapat melihat detail dari preoder belum selesai 
    user dapat melihat detail dari preoder selesai 
    user dapat membuat preorder
    user dapat membuat proder menggunakan methode pembayaran transfer
    user dapat membuat preoder menggunakan methode pembayaran shoppe
    user dapat membuat preoder menggunakan methode pembayaran offline
    user dapat membuat preoder menggunakan methode pembayaran lainnya
    gagal membuat preoder ketika field tanggal kosong
    Total Harga Terhitung Otomatis Berdasarkan Jumlah Produk dan Harga Satuan
    gagal membuat preoder ketika tanggal DP lebih dari tanggal yang diinputkan
    gagal membuat preoder ketika field tanggalDP kosong
    gagal membuat preoder ketika field jumlahDP kosong
    validasi jumlah DP tidak boleh lebih dari total harga
    gagal membuat DP ketika field nama kosong
    gagal membuat DP ketika field email kosong
    gagal membuat DP ketika field alamat kosong
    gagal membuat DP ketika field telepon kosong
    gagal membua preoder ketika product stok sedang kosong
    validasi field jumlah tidak boleh 0 ataupun -1
    validasi field jumlah product tidak boleh melebihi dari stok yang tersedia
    field jumlah DP tidak boleh 0 ataupun -1
    Gagal Membuat Preorder Ketika Total Harga Tidak Sesuai dengan Perhitungan Otomatis
    superadmin/owder dapat mengedit status preoder yang belum selesai ke selesai
    gagal ke halaman edit transaksi ketika login menggunaakn role admin
    gagal update transaksi ketika login menggunaakn role admin
     */
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();

        // Refresh database and run all seeders
        $this->artisan('db:seed');
    }

    public function test_succesfull_get_preoder()
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
        // Prepare the data for creating a transaction
        $preordersData = [
            'tanggal' => now()->format('Y-m-d'),
            'jumlah' => 3,
            'total' => 100000 * 3,
            'nama' => 'Jhone Doe',
            'email' => 'jhone.doe@gmail.com',
            'alamat' => '123 Street, City',
            'telepon' => '081234567890',
            'tanggal_dp' => now()->format('Y-m-d'),
            'jumlah_dp' => '50000',
            'product' => $product->id,
            'methode_pembayaran' => $methodePembayaranLainnya->id,
            'keterangan' => 'Test Keterangan'

        ];
        $response = $this->post(route('preorders.store'), $preordersData);
        $preoder = Transaksi::latest()->first();
        // dd($preoder);
        $product->refresh();
        $preoder->refresh();

        // Periksa bahwa permintaan GET berhasil
        $response = $this->get(route('preorders.index'));
        $response->assertOk();

        $this->get(route('preorders.index'))->assertOk();
        $this->get(route('preorders.index'))->assertSee($product->total);
        $this->get(route('preorders.index'))->assertSee($product->is_complete);
    }
    public function test_succesfull_get_preoder_by_name_product()
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
        $response = $this->post(route('preorders.store'), $transaksiData);
        $transaksi = Transaksi::latest()->first();
        $product->refresh();
        $transaksi->refresh();
        // Search for the transaction by name
        $this->get('admin/preorder?search=product')
            ->assertOk()
            ->assertSee($product->nama_product);

    }
    public function test_succesfull_get_preoder_by_name_nama_pembeli()
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
        $response = $this->post(route('preorders.store'), $transaksiData);
        $transaksi = Transaksi::latest()->first();
        $product->refresh();
        $transaksi->refresh();
        // Search for the transaction by name
        $this->get('admin/preorder?search=' . now()->format('Y-m-d'))
            ->assertOk()
            ->assertSee($product->nama_product);
    }

    public function test_succesfull_get_undone_preoder_detail()
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
        $response = $this->post(route('preorders.store'), $transaksiData);
        $transaksi = Transaksi::latest()->first();
        $product->refresh();
        $transaksi->refresh();
        // / Ambil detail transaksi menggunakan ID yang valid
        $response = $this->get(route('preorders.detail', $transaksi->id))->assertSee($transaksi->is_complete);

        // Pastikan respons berhasil dan memastikan is_complete = 0
        $this->assertDatabaseHas('transaksis', [
            'id' => $transaksi->id,
            'is_complete' => 0,
        ]);


    }
    public function test_succesfull_get_done_preoder_detail()
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



        $preorderUpdated = Transaksi::with(['pembelis', 'products', 'methode_pembayaran', 'preorders'])
            ->findOrFail($preorderId);

        $this->get(route('preorders.detail', $preorderUpdated->id))->assertSee($preorderUpdated->is_complete);

        // Pastikan respons berhasil dan memastikan is_complete = 0
        $this->assertDatabaseHas('transaksis', [
            'id' => $preorderUpdated->id,
            'is_complete' => 1,
        ]);
    }

    public function test_user_can_create_preorder()
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
        $response = $this->post(route('preorders.store'), $transaksiData);
        $transaksi = Transaksi::latest()->first();
        $product->refresh();
        $transaksi->refresh();
        $this->assertDatabaseHas('transaksis', [
            'id' => $transaksi->id,
            'is_complete' => 0,
        ]);
    }

    public function test_user_can_create_preorder_transfer()
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
            'methode_pembayaran' => $methodePembayaranTransfer->id,
            'keterangan' => 'Test Keterangan'

        ];
        $response = $this->post(route('preorders.store'), $transaksiData);
        $transaksi = Transaksi::latest()->first();
        $product->refresh();
        $transaksi->refresh();
        $this->assertDatabaseHas('transaksis', [
            'id' => $transaksi->id,
            'is_complete' => 0,
            'methode_pembayaran_id' => $methodePembayaranTransfer->id
        ]);
    }

    public function test_user_can_create_preorder_shoppe()
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
            'methode_pembayaran' => $methodePembayaranShopee->id,
            'keterangan' => 'Test Keterangan'

        ];
        $response = $this->post(route('preorders.store'), $transaksiData);
        $transaksi = Transaksi::latest()->first();
        $product->refresh();
        $transaksi->refresh();
        $this->assertDatabaseHas('transaksis', [
            'id' => $transaksi->id,
            'is_complete' => 0,
            'methode_pembayaran_id' => $methodePembayaranShopee->id
        ]);
    }

    public function test_user_can_create_preorder_offline()
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
            'methode_pembayaran' => $methodePembayaranOffline->id,
            'keterangan' => 'Test Keterangan'

        ];
        $response = $this->post(route('preorders.store'), $transaksiData);
        $transaksi = Transaksi::latest()->first();
        $product->refresh();
        $transaksi->refresh();
        $this->assertDatabaseHas('transaksis', [
            'id' => $transaksi->id,
            'is_complete' => 0,
            'methode_pembayaran_id' => $methodePembayaranOffline->id
        ]);
    }

    public function test_user_can_create_preorder_other_method()
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
            'methode_pembayaran' => $methodePembayaranLainnya->id,
            'keterangan' => 'Test Keterangan'

        ];
        $response = $this->post(route('preorders.store'), $transaksiData);
        $transaksi = Transaksi::latest()->first();
        $product->refresh();
        $transaksi->refresh();
        $this->assertDatabaseHas('transaksis', [
            'id' => $transaksi->id,
            'is_complete' => 0,
            'methode_pembayaran_id' => $methodePembayaranLainnya->id
        ]);
    }
    public function test_user_cannot_create_preoder_with_empty_date()
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
        // Prepare the data for creating a transaction
        $transaksiData = [
            'tanggal' => null,
            'jumlah' => 1,
            'total' => '100000',
            'nama' => 'John Doe',
            'email' => 'john.doe@example.com',
            'alamat' => '123 Street, City',
            'telepon' => '081234567890',
            'tanggal_dp' => now()->format('Y-m-d'),
            'jumlah_dp' => '50000',
            'product' => $product->id,
            'methode_pembayaran' => $methodePembayaranTransfer->id,
            'keterangan' => 'Test Keterangan'

        ];
        $response = $this->post(route('preorders.store'), $transaksiData);
        // $transaksi = Transaksi::latest()->first();
        // dd($transaksi);
        // $product->refresh();
        // $transaksi->refresh();
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'tanggal' => 'The tanggal field is required.'
        ]);

    }
    public function test_validate_otomatic_calculate_total_harga()
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
        // Prepare the data for creating a transaction
        $transaksiData = [
            'tanggal' => now()->format('Y-m-d'),
            'jumlah' => 3,
            'total' => 100000 * 3,
            'nama' => 'John Doe',
            'email' => 'john.doe@example.com',
            'alamat' => '123 Street, City',
            'telepon' => '081234567890',
            'tanggal_dp' => now()->format('Y-m-d'),
            'jumlah_dp' => '50000',
            'product' => $product->id,
            'methode_pembayaran' => $methodePembayaranLainnya->id,
            'keterangan' => 'Test Keterangan'

        ];
        $response = $this->post(route('preorders.store'), $transaksiData);
        $transaksi = Transaksi::latest()->first();
        $product->refresh();
        // $transaksi->refresh();
        $this->assertSame($product->harga * 3, $transaksi->total_harga);
    }
    public function test_user_cannot_create_preoder_tanggalDP_more_than_tanggal()
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
        // Prepare the data for creating a transaction
        $transaksiData = [
            'tanggal' => now()->format('Y-m-d'),
            'jumlah' => 3,
            'total' => 100000 * 3,
            'nama' => 'John Doe',
            'email' => 'john.doe@example.com',
            'alamat' => '123 Street, City',
            'telepon' => '081234567890',
            'tanggal_dp' => now()->addDays(1)->format('Y-m-d'),
            'jumlah_dp' => '50000',
            'product' => $product->id,
            'methode_pembayaran' => $methodePembayaranLainnya->id,
            'keterangan' => 'Test Keterangan'

        ];
        $response = $this->post(route('preorders.store'), $transaksiData);
        $transaksi = Transaksi::latest()->first();
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'tanggal_dp' => 'Tanggal DP tidak boleh lebih dari tanggal transaksi.'
        ]);
        // dd($response);
        // $product->refresh();
        // $transaksi->refresh();
    }
    public function test_user_cannot_create_preoder_with_empty_tanggaldp()
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
        // Prepare the data for creating a transaction
        $transaksiData = [
            'tanggal' => now()->format('Y-m-d'),
            'jumlah' => 3,
            'total' => 100000 * 3,
            'nama' => 'John Doe',
            'email' => 'john.doe@example.com',
            'alamat' => '123 Street, City',
            'telepon' => '081234567890',
            'tanggal_dp' => null,
            'jumlah_dp' => '50000',
            'product' => $product->id,
            'methode_pembayaran' => $methodePembayaranLainnya->id,
            'keterangan' => 'Test Keterangan'

        ];
        $response = $this->post(route('preorders.store'), $transaksiData);
        $transaksi = Transaksi::latest()->first();
        // dd($response);
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'tanggal_dp' => 'The tanggal dp field is required.'
        ]);
    }
    public function test_user_cannot_create_preoder_with_empty_jumlahdp()
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
        // Prepare the data for creating a transaction
        $transaksiData = [
            'tanggal' => now()->format('Y-m-d'),
            'jumlah' => 3,
            'total' => 100000 * 3,
            'nama' => 'John Doe',
            'email' => 'john.doe@example.com',
            'alamat' => '123 Street, City',
            'telepon' => '081234567890',
            'tanggal_dp' => now()->addDays(1)->format('Y-m-d'),
            'jumlah_dp' => null,
            'product' => $product->id,
            'methode_pembayaran' => $methodePembayaranLainnya->id,
            'keterangan' => 'Test Keterangan'

        ];
        $response = $this->post(route('preorders.store'), $transaksiData);
        $transaksi = Transaksi::latest()->first();
        // dd($response);
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'jumlah_dp' => 'The jumlah dp field is required.'
        ]);
    }
    public function test_validate_jumlahdp_cannot_more_than_total_harga()
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
        // Prepare the data for creating a transaction
        $transaksiData = [
            'tanggal' => now()->format('Y-m-d'),
            'jumlah' => 3,
            'total' => 100000 * 3,
            'nama' => 'John Doe',
            'email' => 'john.doe@example.com',
            'alamat' => '123 Street, City',
            'telepon' => '081234567890',
            'tanggal_dp' => now()->format('Y-m-d'),
            'jumlah_dp' => '500000',
            'product' => $product->id,
            'methode_pembayaran' => $methodePembayaranLainnya->id,
            'keterangan' => 'Test Keterangan'

        ];
        $response = $this->post(route('preorders.store'), $transaksiData);
        $transaksi = Transaksi::latest()->first();
        // dd($response);
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'jumlah_dp' => 'Jumlah DP tidak boleh lebih dari total harga.'
        ]);
    }
    public function test_user_cannot_create_preoder_with_empty_name()
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
        // Prepare the data for creating a transaction
        $transaksiData = [
            'tanggal' => now()->format('Y-m-d'),
            'jumlah' => 3,
            'total' => 100000 * 3,
            'nama' => null,
            'email' => 'john.doe@example.com',
            'alamat' => '123 Street, City',
            'telepon' => '081234567890',
            'tanggal_dp' => now()->format('Y-m-d'),
            'jumlah_dp' => '50000',
            'product' => $product->id,
            'methode_pembayaran' => $methodePembayaranLainnya->id,
            'keterangan' => 'Test Keterangan'

        ];
        $response = $this->post(route('preorders.store'), $transaksiData);
        $transaksi = Transaksi::latest()->first();
        // dd($response);
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'nama' => 'The nama field is required.'
        ]);
    }
    public function test_user_cannot_create_preoder_with_empty_email()
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
        // Prepare the data for creating a transaction
        $transaksiData = [
            'tanggal' => now()->format('Y-m-d'),
            'jumlah' => 3,
            'total' => 100000 * 3,
            'nama' => 'Jhone Doe',
            'email' => null,
            'alamat' => '123 Street, City',
            'telepon' => '081234567890',
            'tanggal_dp' => now()->format('Y-m-d'),
            'jumlah_dp' => '50000',
            'product' => $product->id,
            'methode_pembayaran' => $methodePembayaranLainnya->id,
            'keterangan' => 'Test Keterangan'

        ];
        $response = $this->post(route('preorders.store'), $transaksiData);
        $transaksi = Transaksi::latest()->first();
        // dd($response);
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'email' => 'The email field is required.'
        ]);
    }
    public function test_user_cannot_create_preoder_with_empty_address()
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
        // Prepare the data for creating a transaction
        $transaksiData = [
            'tanggal' => now()->format('Y-m-d'),
            'jumlah' => 3,
            'total' => 100000 * 3,
            'nama' => 'Jhone Doe',
            'email' => 'jhone.doe@email.com',
            'alamat' => null,
            'telepon' => '081234567890',
            'tanggal_dp' => now()->format('Y-m-d'),
            'jumlah_dp' => '50000',
            'product' => $product->id,
            'methode_pembayaran' => $methodePembayaranLainnya->id,
            'keterangan' => 'Test Keterangan'

        ];
        $response = $this->post(route('preorders.store'), $transaksiData);
        $transaksi = Transaksi::latest()->first();
        // dd($response);
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'alamat' => 'The alamat field is required.'
        ]);
    }
    public function test_user_cannot_create_preoder_with_empty_phone()
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
        // Prepare the data for creating a transaction
        $transaksiData = [
            'tanggal' => now()->format('Y-m-d'),
            'jumlah' => 3,
            'total' => 100000 * 3,
            'nama' => 'Jhone Doe',
            'email' => 'jhone.doe@email.com',
            'alamat' => '123 Street, City',
            'telepon' => null,
            'tanggal_dp' => now()->format('Y-m-d'),
            'jumlah_dp' => '50000',
            'product' => $product->id,
            'methode_pembayaran' => $methodePembayaranLainnya->id,
            'keterangan' => 'Test Keterangan'

        ];
        $response = $this->post(route('preorders.store'), $transaksiData);
        $transaksi = Transaksi::latest()->first();
        // dd($response);
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'telepon' => 'The telepon field is required.'
        ]);
    }
    public function test_user_cannot_create_preoder_with_empty_product_stock()
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
            'total' => $product->harga * 10,
            'keterangan' => 'Test Keterangan',
            'jumlah' => 10,
            'is_complete' => 1
        ];

        // Menyimpan transaksi
        $response = $this->post(route('transaksis.store'), $transaksiData);
        $transaksi = Transaksi::latest()->first();
        $transaksiId = $transaksi->id;

        $transaksiData2 = [
            'tanggal' => now()->format('Y-m-d'),
            'jumlah' => 3,
            'total' => 100000 * 3,
            'nama' => 'Jhone Doe',
            'email' => 'jhone.doe@email.com',
            'alamat' => '123 Street, City',
            'telepon' => '081234567890',
            'tanggal_dp' => now()->format('Y-m-d'),
            'jumlah_dp' => '50000',
            'product' => $product->id,
            'methode_pembayaran' => $methodePembayaran->id,
            'keterangan' => 'Test Keterangan'

        ];
        $responseTransaksi2 = $this->post(route('preorders.store'), $transaksiData2);
        // dd($responseTransaksi2);
        $responseTransaksi2->assertStatus(302);
        $responseTransaksi2->assertSessionHasErrors([
            'product' => 'Stok produk tidak tersedia.'
        ]);
    }
    public function test_validate_jumlah_cannot_zero_or_minus()
    {
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
            'jumlah' => -1,
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
        // dd($response);
        $preorder = Transaksi::latest()->first();
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'jumlah' => 'The jumlah field must be at least 1.'
        ]);

    }
    public function test_validate_jumlah_product_cannot_more_than_stock()
    {
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
            'jumlah' => 11,
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
        // dd($response);
        $preorder = Transaksi::latest()->first();
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'jumlah' => 'Jumlah yang diminta melebihi stok yang tersedia.'
        ]);
    }
    public function test_validate_jumlahdp_cannot_zero_or_minus()
    {
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
            'jumlah_dp' => '-50000',
            'product' => $product->id,
            'methode_pembayaran' => $methodePembayaran->id,
            'keterangan' => 'Test Keterangan'
        ];

        // Create a transaction
        $response = $this->post(route('preorders.store'), $transaksiData);
        // dd($response);
        $preorder = Transaksi::latest()->first();
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'jumlah_dp' => 'The jumlah dp field must be at least 1.'
        ]);
    }
    public function test_cannot_create_when_total_harga_does_not_match_automatic_calculation()
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
        // Prepare the data for creating a transaction
        $transaksiData = [
            'tanggal' => now()->format('Y-m-d'),
            'jumlah' => 3,
            'total' => 100000*3,
            'nama' => 'John Doe',
            'email' => 'john.doe@example.com',
            'alamat' => '123 Street, City',
            'telepon' => '081234567890',
            'tanggal_dp' => now()->format('Y-m-d'),
            'jumlah_dp' => '50000',
            'product' => $product->id,
            'methode_pembayaran' => $methodePembayaranLainnya->id,
            'keterangan' => 'Test Keterangan'

        ];
        $response = $this->post(route('preorders.store'), $transaksiData);
        $transaksi = Transaksi::latest()->first();
        $product->refresh();
        $transaksi->refresh();
        $this->assertSame($product->harga * 3, $transaksi->total_harga);
    }
    public function test_superadmin_can_edit_preoder()
    {
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


        $preorderUpdated = Transaksi::with(['pembelis', 'products', 'methode_pembayaran', 'preorders'])
            ->findOrFail($preorderId);
        // Pastikan transaksi diperbarui dalam database
        $this->assertDatabaseHas('transaksis', [
            'id' => $preorderId,
            'is_complete' => 1,
            'is_Preorder' => 1,
        ]);
    }
    public function test_admin_cannot_edit_preoder()
    {
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
        // Prepare the data for creating a transaction
        $preordersData = [
            'tanggal' => now()->format('Y-m-d'),
            'jumlah' => 3,
            'total' => 100000 * 3,
            'nama' => 'Jhone Doe',
            'email' => 'jhone.doe@gmail.com',
            'alamat' => '123 Street, City',
            'telepon' => '081234567890',
            'tanggal_dp' => now()->format('Y-m-d'),
            'jumlah_dp' => '50000',
            'product' => $product->id,
            'methode_pembayaran' => $methodePembayaranLainnya->id,
            'keterangan' => 'Test Keterangan'

        ];
        $response = $this->post(route('preorders.store'), $preordersData);
        $preorder = Transaksi::latest()->first();
        // dd($preoder);

        $product->refresh();
        $preorder->refresh();

        $response = $this->get(route('preorders.edit', $preorder->id))->assertStatus(403);

    }
    public function test_admin_cannot_update_preoder()
    {
        // Log in the user
        $response = $this->post(route('authentication'), [
            'nama' => 'admin',
            'password' => 'admin',
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
        $response = $this->patch(route('preorders.update', $preorderId), $updateData)->assertStatus(403);
        ;
    }


}
