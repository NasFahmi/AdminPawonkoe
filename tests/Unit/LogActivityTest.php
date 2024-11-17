<?php


namespace Tests\Unit;

use App\Models\BebanKewajiban;
use App\Models\CicilanHutang;
use App\Models\Foto;
use App\Models\Hutang;
use App\Models\JenisModal;
use App\Models\MethodePembayaran;
use App\Models\Modal;
use App\Models\Piutang;
use App\Models\Preorder;
use App\Models\Product;
use App\Models\Produksi;
use App\Models\TemporaryImage;
use App\Models\Transaksi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Storage;
use Tests\TestCase;
use Spatie\Activitylog\Models\Activity;

// ! ATTENTION PEOPLE
// ! THIS IS INTEGRATION TESTING, NOT UNIT TEST


class LogActivityTest extends TestCase
{
    use RefreshDatabase;
    protected $ipAddr = '127.0.0.1';

    protected function setUp(): void
    {
        parent::setUp();

        // Refresh database and run all seeders
        $this->artisan('db:seed');
    }

    /**
     * A basic unit test example.
     *
    /**
     * create data log aktivitas ketika login berhasil
     * craete data log aktivitas ketika login trigger rate limitter
     *create data log aktivitas ketika logout
     *create data log aktivitas ketika create product
     *create data log aktivitas ketika edit product
     *create data log aktivitas ketika delete product
     *create data log aktivitas ketika create transaksi
     *create data log aktivitas ketika edit transaksi
     *create data log aktivitas ketika delete transaksi
     *create data log aktivitas ketika create transaksi preorder
     *create data log aktivitas ketika edit transaksi preorder
     *create data log aktivitas ketika delete transaksi preorder
     *create data log aktivitas ketika create piutang
     *create data log aktivitas ketika edit piutang
     *create data log aktivitas ketika delete piutang
     *create data log aktivitas ketika create hutang
     *create data log aktivitas ketika edit hutang
     *create data log aktivitas ketika delete hutang
     *create data log aktivitas ketika create beban kewajiban
     *create data log aktivitas ketika edit beban kewajiban
     *create data log aktivitas ketika delete beban kewajiban
     *create data log aktivitas ketika create produksi
     *create data log aktivitas ketika edit produksi
     *create data log aktivitas ketika delete produksi
     */
    public function test_create_data_log_activities_when_user_successful_login(): void
    {
        // Simulate a login request
        // $owner = User::create([
        //     "nama" => "pawonkoe",
        //     "email" => "pawonkoe@gmail.com",
        //     "password" => bcrypt('pawonkoe')
        // ]);

        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        // Assert that the response status is 302 (redirect)
        $response->assertStatus(302);


        // Assert that the response redirects to the dashboard
        $response->assertRedirect(route('admin.dashboard'));

        // Check if a login activity log entry was created with the expected description
        $log = Activity::where('event', 'login_succesfull')
            ->where('description', "User pawonkoe logged into account from IP $this->ipAddr")
            ->first();

        // Assert that the log was created
        $this->assertNotNull($log);
    }
    public function test_create_data_log_activities_when_user_trigger_rate_limiter()
    {
        // Simulate a login request
        // Simulasikan 3 percobaan login yang gagal
        for ($i = 0; $i < 3; $i++) {
            $this->post(route('authentication'), [
                'nama' => 'admin',
                'password' => 'passwordsalah',
            ]);
        }
        // Percobaan login ke-4 seharusnya memicu rate limiter
        $response = $this->post(route('authentication'), [
            'nama' => 'admin',
            'password' => 'passwordsalah',
        ]);
        // dd($response);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));

        // Optional: Retrieve and output IP address

        // Check if a login activity log entry was created with the expected description
        $log = Activity::where('event', 'login_attempt_rate_limited')
            ->where('description', "Login rate limited for IP $this->ipAddr")
            ->first();

        // Assert that the log was created
        $this->assertNotNull($log);
    }
    public function test_create_data_log_activites_when_succesfull_logout()
    {

        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        // logout
        // Lakukan request logout
        $response = $this->get(route('logout'));

        // Pastikan redirect ke halaman login
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        // Optional: Retrieve and output IP address

        $log = Activity::where('event', 'logout_succesfull')
            ->where('description', "User pawonkoe logout from account with IP $this->ipAddr")
            ->first();
        // dd($log);

        // Assert that the log was created
        $this->assertNotNull($log);
    }
    protected function setupTemporaryImages()
    {
        // Create temporary image records
        TemporaryImage::create([
            'folder' => 'image1',
            'file' => 'test-image1.jpg'
        ]);

        TemporaryImage::create([
            'folder' => 'image2',
            'file' => 'test-image2.jpg'
        ]);

        // Create temporary image files
        Storage::putFileAs(
            'public/images/tmp/image1',
            UploadedFile::fake()->image('test-image1.jpg'),
            'test-image1.jpg'
        );

        Storage::putFileAs(
            'public/images/tmp/image2',
            UploadedFile::fake()->image('test-image2.jpg'),
            'test-image2.jpg'
        );
    }

    public function test_create_data_log_activities_when_successful_create_product()
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

        $response = $this->post(route('products.store'), $productData);
        // Check if redirect is successful
        // dd($response->getContent());
        // $response->assertStatus(302);
        $this->assertDatabaseHas('products', [
            'nama_product' => 'Test Product',
            'harga' => '100000',
            'stok' => '10',
            'tersedia' => '1'
        ]);

        // Check if log is created
        $log = Activity::where('event', 'add_product')
            ->where('description', 'User pawonkoe add a new product')
            ->first();
        $this->assertNotNull($log);
    }

    public function test_create_data_log_activities_when_successful_edit_product()
    {
        // Login the user
        $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        // First, create a product
        $temporaryFolder = uniqid('image-', true);
        TemporaryImage::create([
            'folder' => $temporaryFolder,
            'file' => 'test-image.jpg'
        ]);

        // Create temporary image in storage
        Storage::disk('public')->put(
            "images/tmp/{$temporaryFolder}/test-image.jpg",
            UploadedFile::fake()->image('test-image.jpg')->size(100)
        );

        // Create initial product
        $productData = [
            'nama_product' => 'Test Product',
            'harga' => '100000',
            'deskripsi' => 'Test Description',
            'link_shopee' => 'https://shopee.com/test',
            'stok' => '10',
            'spesifikasi_product' => 'Test Specifications',
            'images' => [json_encode([$temporaryFolder])],
            'varian' => ['Red', 'Blue']
        ];

        $this->post(route('products.store'), $productData);
        $product = Product::first();

        // Create new image for update
        $newPhotoFolder = uniqid('image-', true);
        $newPhotoName = 'new-test-image.jpg';

        Storage::disk('public')->put(
            "images/product/{$product->slug}/{$newPhotoName}",
            UploadedFile::fake()->image($newPhotoName)->size(100)
        );

        // Get existing photo path
        $existingPhoto = Foto::where('product_id', $product->id)->first()->foto;

        // Prepare update data
        $updateData = [
            'nama_product' => 'Updated Product',
            'harga' => '200000',
            'deskripsi' => 'Updated Description',
            'link_shopee' => 'https://shopee.com/updated',
            'stok' => '20',
            'spesifikasi_product' => 'Updated Specifications',
            'varian' => ['Green', 'Yellow'],
            'images' => [
                $existingPhoto, // Keep existing photo
                json_encode([$newPhotoName]) // Add new photo
            ]
        ];

        // Perform the update
        $response = $this->patch(route('products.update', $product->id), $updateData);
        $response->assertRedirect(route('products.index'))
            ->assertSessionHas('success', 'Product has been updated successfully');
        // Verifikasi bahwa log aktivitas untuk edit produk telah berhasil dibuat
        $log = Activity::where('event', 'update_product')
            ->where('description', 'User pawonkoe edit a product')
            ->first();
        $this->assertNotNull($log);
    }

    public function test_create_data_log_activites_when_succesfull_delete_product()
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

        $response = $this->post(route('products.store'), $productData);
        $response->assertStatus(302);
        // Check if redirect is successful
        // dd($response->getContent());
        $product = Product::first();
        $productId = $product->id; // Mengambil ID produk dari respons JSON
        // Menghapus produk
        $response = $this->delete(route('products.destroy', $productId));

        // Memastikan pengalihan ke rute yang diharapkan setelah hapus
        $response->assertRedirect(route('products.index'));
        $response->assertSessionHas('success', 'Product has been deleted successfully');

        // Memastikan produk tidak lagi tersedia (tersedia = false)
        $this->assertDatabaseHas('products', [
            'id' => $productId,
            'tersedia' => false,
        ]);

        // Memastikan log aktivitas untuk penghapusan produk telah berhasil dibuat
        // Verifikasi bahwa log aktivitas untuk edit produk telah berhasil dibuat
        $log = Activity::where('event', 'delete_product')
            ->where('description', 'User pawonkoe delete a product')
            ->first();
        $this->assertNotNull($log);

    }

    public function test_create_data_log_activities_when_succesfull_create_transaksi()
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

        // Verifikasi status
        // dd($response->status);
        // dd(Transaksi::all());
        // dd(Activity::all());
        // Verifikasi log aktivitas
        // format activity di transaksi
        // activity()
        // ->causedBy(auth()->user())
        // ->performedOn($transaksi)
        // ->event('add_transaksi')
        // ->withProperties(['id' => $transaksi->id])
        // ->log('User ' . auth()->user()->nama . ' add a transaksi');

        $log = Activity::where('event', 'add_transaksi')
            ->where('description', 'User pawonkoe add a transaksi')
            ->first();

        $this->assertNotNull($log);
    }



    public function test_create_data_log_activities_when_successful_edit_transaksi()
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

        // Update data transaksi
        $transaksiUpdateData = [
            'product' => $productId,
            'total' => $product->total_harga,
            'jumlah' => $product->jumlah,
            'is_complete' => 1 // Mengubah status menjadi complete untuk memicu event dan log
        ];

        $responseUpdate = $this->patch(route('transaksis.update', $transaksi->id), $transaksiUpdateData); // Ganti post dengan patch

        $transaksiUpdated = Transaksi::where('id', $transaksi->id)->first();
        // dd($transaksiUpdated);
        // Verifikasi status
        $responseUpdate->assertStatus(302); // Pastikan update berhasil

        // Verifikasi log aktivitas
        $log = Activity::where('event', 'update_transaksi')
            ->where('description', 'User pawonkoe update a transaksi')
            ->first();

        $this->assertNotNull($log);
    }
    public function test_create_data_log_activities_when_succesfull_create_preoder()
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
        // Assert that the transaction is in the database
        $log = Activity::where('event', 'add_transaksi_preorder')
            ->where('description', 'User pawonkoe add a transaksi preorder')
            ->first();

        $this->assertNotNull($log);
    }

    public function test_create_data_log_activities_when_succesfull_edit_preoder()
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

        // // Assert that the response is a redirect
        // $response->assertRedirect(route('preorders.index'));

        // Assert that the success message is in the session
        // $this->assertSessionHas('success', 'Preorder has been updated successfully');

        // // // Assert that the transaction is updated in the database
        // $this->assertDatabaseHas('preorders', [
        //     'id' => $preorderId,
        // ]);

        $preorderUpdated = Transaksi::with(['pembelis', 'products', 'methode_pembayaran', 'preorders'])
            ->findOrFail($preorderId);
        // dd(Activity::latest()->first());
        // dd($preorderUpdated);

        // Check for activity log
        $log = Activity::where('event', 'update_transaksi_preorder')
            ->where('description', 'User pawonkoe update a transaksi preorder')
            ->first();

        $this->assertNotNull($log);
    }
    public function test_create_data_log_activities_when_succesfull_create_piutang()
    {
        $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        $data = [
            'nama_toko' => 'Toko A',
            'sewa_titip' => 100000,
            'tanggal_disetorkan' => now(),
            'catatan' => 'Catatan contoh',
            'product' => [
                [
                    'product' => 'Produk 1',
                    'quantity' => 2,
                    'price' => 50000,
                ],
            ],
            'image' => [
                // Menggunakan file dummy
                UploadedFile::fake()->image('image1.jpg'),
            ],
        ];

        $response = $this->post(route('piutang.store'), $data);

        $response->assertRedirect(route('piutang.index'));
        $this->assertDatabaseHas('piutangs', [
            'nama_toko' => 'Toko A',
        ]);

        // $this->assertDatabaseHas('activity_log', [
        //     'event' => 'add_piutang',
        //     'description' => 'User pawonkoe add a piutang'
        // ]);
        $log = Activity::where('event', 'add_piutang')
            ->where('description', 'User pawonkoe add a piutang')
            ->first();

        $this->assertNotNull($log);
    }

    public function test_create_data_log_activities_when_succesfull_edit_piutang()
    {
        $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        $data = [
            'nama_toko' => 'Toko A',
            'sewa_titip' => 100000,
            'tanggal_disetorkan' => now(),
            'catatan' => 'Catatan contoh',
            'product' => [
                [
                    'product' => 'Produk 1',
                    'quantity' => 2,
                    'price' => 50000,
                ],
            ],
            'image' => [
                // Menggunakan file dummy
                UploadedFile::fake()->image('image1.jpg'),
            ],
        ];

        $response = $this->post(route('piutang.store'), $data);

        $piutang = Piutang::latest()->first();
        // $response->assertRedirect(route('piutang.index'));
        $this->assertDatabaseHas('piutangs', [
            'nama_toko' => 'Toko A',
        ]);

        $data = [
            'is_complete' => 1,
            'tanggal_lunas' => now(),
        ];

        $response = $this->patch(route('piutang.update', $piutang->id), $data);

        $response->assertRedirect(route('piutang.index'));

        $log = Activity::where('event', 'update_piutang')
            ->where('description', 'User pawonkoe update a piutang')
            ->first();

        $this->assertNotNull($log);
    }

    public function test_create_data_log_activities_when_succesfull_delete_piutang()
    {
        $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        $data = [
            'nama_toko' => 'Toko A',
            'sewa_titip' => 100000,
            'tanggal_disetorkan' => now(),
            'catatan' => 'Catatan contoh',
            'product' => [
                [
                    'product' => 'Produk 1',
                    'quantity' => 2,
                    'price' => 50000,
                ],
            ],
            'image' => [
                // Menggunakan file dummy
                UploadedFile::fake()->image('image1.jpg'),
            ],
        ];

        $response = $this->post(route('piutang.store'), $data);
        $piutang = Piutang::latest()->first();

        $response = $this->delete(route('piutang.destroy', $piutang->id));
        $log = Activity::where('event', 'delete_piutang')
            ->where('description', 'User pawonkoe deleted a piutang')
            ->first();

        $this->assertNotNull($log);

    }
    public function test_create_data_log_activities_when_succesfull_create_hutang()
    {

        $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $data = [
            'nama' => 'Bank BRI',
            'catatan' => 'Hutang buat bahan',
            'jumlahHutang' => 1000000,
            'tanggal_lunas' => null,
            'tenggat_waktu' => '2024-10-25',
            'nominal' => 100000,
            'status' => 0,
        ];

        $response = $this->post(route('hutang.store'), $data);
        $hutang = Hutang::latest()->first();

        // dd($hutang);
        $log = Activity::where('event', 'add_hutang')
            ->where('description', 'User pawonkoe add a new hutang')
            ->first();

        $this->assertNotNull($log);
    }
    public function test_create_data_log_activities_when_succesfull_edit_hutang()
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.dashboard'));

        $hutang = Hutang::create([
            'nama' => 'Bank ABC',
            'catatan' => 'Catatan hutang',
            'status' => 0,
            'jumlah_hutang' => 500000,
            'tenggat_waktu' => '2024-10-30',
            'tanggal_lunas' => null,
        ]);
        $id = $hutang->id;

        CicilanHutang::create([
            'nominal' => 50000,
            'hutangId' => $id,
        ]);

        $updatedData = [
            'nama' => 'Bank XYZ',
            'id' => $id,
            'catatan' => 'Catatan hutang diperbarui',
            'status' => 0,
            'jumlahHutang' => 600000,
            'tenggat_waktu' => '2024-10-31',
            'tanggal_lunas' => null,
            'nominal' => 60000,
        ];

        $response = $this->patch(route('hutang.update', $id), $updatedData);

        // dd($hutang);
        $log = Activity::where('event', 'edit_hutang')
            ->where('description', 'User pawonkoe update a hutang')
            ->first();

        $this->assertNotNull($log);
    }
    public function test_create_data_log_activities_when_succesfull_delete_hutang()
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $hutang = Hutang::create([
            'nama' => 'Bank ABC',
            'catatan' => 'Catatan hutang',
            'status' => 0,
            'jumlah_hutang' => 500000,
            'tenggat_waktu' => '2024-10-30',
            'tanggal_lunas' => null,
        ]);

        $this->assertDatabaseHas('hutangs', [
            'id' => $hutang->id,
            'nama' => 'Bank ABC',
        ]);

        $response = $this->delete(route('hutang.destroy', $hutang->id));
        $log = Activity::where('event', 'delete_hutang')
            ->where('description', 'User pawonkoe delete a hutang')
            ->first();

        $this->assertNotNull($log);
    }
    public function test_create_data_log_activities_when_succesfull_create_beban_kewajiban()
    {
        $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        $data = [
            'jenis' => 'Test Jenis',
            'nama' => 'Test Nama',
            'nominal' => 1000,
            'tanggal' => now()->format('Y-m-d'),
        ];

        $response = $this->post(route('beban-kewajibans.store'), $data);
        // Assert that the activity was logged
        $log = Activity::where('event', 'add_beban_kewajiban')
            ->where('description', 'User pawonkoe add a new beban kewajiban')
            ->first();

        $this->assertNotNull($log);

    }
    public function test_create_data_log_activities_when_succesfull_edit_beban_kewajiban()
    {
        $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        $data = [
            'jenis' => 'Test Jenis',
            'nama' => 'Test Nama',
            'nominal' => 1000,
            'tanggal' => now()->format('Y-m-d'),
        ];

        $response = $this->post(route('beban-kewajibans.store'), $data);
        $bebanKewajiban = BebanKewajiban::latest()->first();
        $data = [
            'jenis' => 'Updated Jenis',
            'nama' => 'Updated Nama',
            'nominal' => 2000,
            'tanggal' => now()->format('Y-m-d'),
        ];

        $response = $this->patch(route('beban-kewajibans.update', $bebanKewajiban->id), $data);

        $log = Activity::where('event', 'update_beban_kewajiban')
            ->where('description', 'User pawonkoe update a beban kewajiban')
            ->first();

        $this->assertNotNull($log);


    }
    public function test_create_data_log_activities_when_succesfull_delete_beban_kewajiban()
    {
        $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        $data = [
            'jenis' => 'Test Jenis',
            'nama' => 'Test Nama',
            'nominal' => 1000,
            'tanggal' => now()->format('Y-m-d'),
        ];

        $response = $this->post(route('beban-kewajibans.store'), $data);
        $bebanKewajiban = BebanKewajiban::latest()->first();
        $response = $this->delete(route('beban-kewajibans.destroy', $bebanKewajiban->id));

        $log = Activity::where('event', 'delete_beban_kewajiban')
            ->where('description', 'User pawonkoe delete a beban kewajiban')
            ->first();

        $this->assertNotNull($log);
    }

    public function test_create_data_log_activities_when_successful_create_produksi()
    {
        // Authenticate the user
        $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        // Create a new Produksi
        $data = [
            'produk' => 'Produk Test',
            'volume' => 10.5,
            'jumlah' => 5,
            'tanggal' => now()->format('Y-m-d'),
        ];
        $this->post(route('produksi.store'), $data);

        // Assert that the log activity has been created
        $log = Activity::where('event', 'add_produksi')
            ->where('description', 'User pawonkoe add a produksi')
            ->first();

        $this->assertNotNull($log);

    }

    public function test_create_data_log_activities_when_successful_edit_produksi()
    {
        // Authenticate the user
        $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        // Create a new Produksi
        $data = [
            'produk' => 'Produk Test',
            'volume' => 10.5,
            'jumlah' => 5,
            'tanggal' => now()->format('Y-m-d'),
        ];
        $this->post(route('produksi.store'), $data);
        $produksi = Produksi::latest()->first();

        // Update the Produksi
        $data = [
            'produk' => 'Updated Product',
            'volume' => 12.0,
            'jumlah' => 3,
            'tanggal' => now()->format('Y-m-d'),
        ];
        $this->patch(route('produksi.update', $produksi->id), $data);

        // Assert that the log activity has been created
        $log = Activity::where('event', 'update_produksi')
            ->where('description', 'User pawonkoe update a produksi')
            ->first();

        $this->assertNotNull($log);
    }

    public function test_create_data_log_activities_when_successful_delete_produksi()
    {
        $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        // Create a new Produksi
        $data = [
            'produk' => 'Produk Test',
            'volume' => 10.5,
            'jumlah' => 5,
            'tanggal' => now()->format('Y-m-d'),
        ];
        $this->post(route('produksi.store'), $data);
        $produksi = Produksi::latest()->first();

        // Delete the Produksi
        $this->delete(route('produksi.destroy', $produksi->id));

        // Assert that the log activity has been created
        $log = Activity::where('event', 'delete_produksi')
            ->where('description', 'User pawonkoe delete a produksi')
            ->first();

        $this->assertNotNull($log);
    }
    public function test_create_data_log_activities_when_succesful_create_modal()
    {
        $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        $modalFisik = JenisModal::create([
            'jenis_modal' => 'Fisik'
        ]);
        $modalFinansial = JenisModal::create([
            'jenis_modal' => 'Finansial'
        ]);
        // Create modal data
        $data = [
            'jenis' => $modalFisik->id, // Assuming valid `jenis_modal_id` exists
            'nama' => 'Test Modal',
            'nominal' => 5000,
            'penyedia' => 'Test Provider',
            'jumlah' => 10,
            'tanggal' => now()->format('Y-m-d'),
        ];
        $this->post(route('modal.store'), $data);
        $modal = Modal::latest()->first();

        $log = Activity::where('event', 'add_modal')
            ->where('description', 'User pawonkoe add a modal')
            ->first();

        $this->assertNotNull($log);

    }
    public function test_create_data_log_activities_when_succesful_edit_modal()
    {
        // Authenticate the user
        $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        // Create modal types
        $modalFisik = JenisModal::create([
            'jenis_modal' => 'Fisik',
        ]);
        $modalFinansial = JenisModal::create([
            'jenis_modal' => 'Finansial',
        ]);

        // Create initial modal data
        $data = [
            'jenis' => $modalFisik->id, // Assuming valid `jenis_modal_id` exists
            'nama' => 'Test Modal',
            'nominal' => 5000,
            'penyedia' => 'Test Provider',
            'jumlah' => 10,
            'tanggal' => now()->format('Y-m-d'),
        ];
        $this->post(route('modal.store'), $data);
        $modal = Modal::latest()->first(); // Fetch the latest modal after creation

        // Prepare data for updating the modal
        $dataUpdated = [
            'jenis' => $modalFinansial->id, // Updated to a new valid `jenis_modal_id`
            'nama' => 'Updated Test Modal',
            'nominal' => 5000,
            'penyedia' => 'Updated Test Provider',
            'jumlah' => 100,
            'tanggal' => now()->format('Y-m-d'),
        ];

        // Perform the update via PUT request (use the correct HTTP method for updating)
        $response = $this->patch(route('modal.update', $modal->id), $dataUpdated);
        // dd($response);
        // Retrieve the log for the update action
        $log = Activity::where('event', 'edit_modal')
            ->where('description', 'User pawonkoe edit a modal')
            ->first();

        // Assert that the log entry exists
        $this->assertNotNull($log);
    }

    public function test_create_data_log_activities_when_succesful_delete_modal()
    {
        $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $modalFisik = JenisModal::create([
            'jenis_modal' => 'Fisik'
        ]);
        $modalFinansial = JenisModal::create([
            'jenis_modal' => 'Finansial'
        ]);
        // Create modal data
        $data = [
            'jenis' => $modalFisik->id, // Assuming valid `jenis_modal_id` exists
            'nama' => 'Test Modal',
            'nominal' => 5000,
            'penyedia' => 'Test Provider',
            'jumlah' => 10,
            'tanggal' => now()->format('Y-m-d'),
        ];
        $this->post(route('modal.store'), $data);
        $modal = Modal::latest()->first();

        $this->delete(route('modal.destroy', $modal->id));

        $log = Activity::where('event', 'delete_modal')
            ->where('description', 'User pawonkoe delete a modal')
            ->first();

        $this->assertNotNull($log);
    }

}
