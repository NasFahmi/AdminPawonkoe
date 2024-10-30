<?php


namespace Tests\Feature;

use App\Models\BebanKewajiban;
use App\Models\Hutang;
use App\Models\Piutang;
use App\Models\Product;
use App\Models\Produksi;
use App\Models\TemporaryImage;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Storage;
use Tests\TestCase;
use Spatie\Activitylog\Models\Activity;

class LogActivityTest extends TestCase
{
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
        $response = $this->post(route('authentication'), [
            'nama' => 'admin',
            'password' => 'admin',
        ]);

        // Assert that the response status is 302 (redirect)
        $response->assertStatus(302);

        // Optional: Retrieve and output IP address
        $ipAddress = $response->getRequest()->ip();


        // Assert that the response redirects to the dashboard
        $response->assertRedirect(route('admin.dashboard'));

        // Check if a login activity log entry was created with the expected description
        $log = Activity::where('event', 'login_succesfull')
            ->where('description', "User admin logged into account from IP $ipAddress")
            ->first();

        // Assert that the log was created
        $this->assertNotNull($log);
    }
    public function test_crate_data_log_activities_when_user_trigger_rate_limiter()
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
        $ipAddress = $response->getRequest()->ip();

        // Check if a login activity log entry was created with the expected description
        $log = Activity::where('event', 'login_attempt_rate_limited')
            ->where('description', "Login rate limited for IP $ipAddress")
            ->first();

        // Assert that the log was created
        $this->assertNotNull($log);
    }
    public function test_create_data_log_activites_when_succesfull_logout()
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'admin',
            'password' => 'admin',
        ]);
        // logout
        $response = $this->get(route('logout'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        // Optional: Retrieve and output IP address
        $ipAddress = $response->getRequest()->ip();

        $log = Activity::where('event', 'logout_succesfull')
            ->where('description', "User admin logout from account with IP $ipAddress")
            ->first();

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

        $productData = [
            'nama_product' => 'Sample Product',
            'harga' => '10000',
            'deskripsi' => 'Deskripsi produk contoh.',
            'link_shopee' => 'https://shopee.co.id/sample-product',
            'stok' => '10',
            'spesifikasi_product' => 'Spesifikasi produk contoh.',
            'images' => [
                json_encode(['image1']),
                json_encode(['image2'])
            ]
        ];

        // Make the request
        $response = $this->post(route('products.store'), $productData);



        // Assert activity log was created
        $this->assertDatabaseHas('activity_log', [
            'event' => 'add_product',
            'log_name' => 'default',
            'description' => 'User pawonkoe add a new product ',
            'subject_type' => Product::class,
        ]);

    }

    public function test_create_data_log_activities_when_successful_edit_product()
    {
        // Login
        $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        // Data untuk produk baru
        $productData = [
            'nama_product' => 'Sample Product',
            'harga' => '10000',
            'deskripsi' => 'Deskripsi produk contoh.',
            'link_shopee' => 'https://shopee.co.id/sample-product',
            'stok' => '10',
            'spesifikasi_product' => 'Spesifikasi produk contoh.',
            'images' => [
                json_encode(['image1']),
                json_encode(['image2'])
            ]
        ];

        // Membuat produk baru
        $this->post(route('products.store'), $productData);

        // Data yang akan digunakan untuk mengedit produk
        $editedProductData = [
            'nama_product' => 'Updated Sample Product',
            'harga' => '12000',
            'deskripsi' => 'Deskripsi produk diperbarui.',
            'link_shopee' => 'https://shopee.co.id/updated-sample-product',
            'stok' => '15',
            'spesifikasi_product' => 'Spesifikasi produk diperbarui.',
            'images' => [
                json_encode(['image1_updated']),
                json_encode(['image2_updated'])
            ]
        ];

        // Ambil ID produk yang baru saja dibuat (asumsikan produk pertama yang dibuat)
        $productId = Product::first()->id;

        // Lakukan permintaan untuk mengedit produk
        $response = $this->put(route('products.update', $productId), $editedProductData);

        // Verifikasi bahwa log aktivitas untuk edit produk telah berhasil dibuat
        $this->assertDatabaseHas('activity_log', [
            'event' => 'update_product',
            'log_name' => 'default',
            'description' => 'User pawonkoe edit a product',
            'subject_type' => Product::class,
        ]);
    }

    public function test_create_data_log_activites_when_succesfull_delete_product()
    {
        // Login
        $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        // Membuat produk baru
        $product = Product::create([
            'nama_product' => 'Sample Product',
            'harga' => '10000',
            'deskripsi' => 'Deskripsi produk contoh.',
            'link_shopee' => 'https://shopee.co.id/sample-product',
            'stok' => '10',
            'spesifikasi_product' => 'Spesifikasi produk contoh.',
            'tersedia' => true,
            'slug' => 'Sample-Product'
        ]);

        // Menghapus produk
        $response = $this->delete(route('products.destroy', $product->id));

        // Memastikan pengalihan ke rute yang diharapkan setelah hapus
        $response->assertRedirect(route('products.index'));
        $response->assertSessionHas('success', 'Product has been deleted successfully');

        // Memastikan produk tidak lagi tersedia (tersedia = false)
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'tersedia' => false,
        ]);

        // Memastikan log aktivitas untuk penghapusan produk telah berhasil dibuat
        $this->assertDatabaseHas('activity_log', [
            'event' => 'delete_product',
            'log_name' => 'default',
            'description' => 'User pawonkoe delete a product ',
            'subject_type' => Product::class,
        ]);
    }

    public function test_create_data_log_activities_when_succesfull_create_transaksi()
    {
        $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $transaksiData = [
            'tanggal' => Carbon::now()->format('Y-m-d'),
            'product' => 1,
            'methode_pembayaran' => 'transfer',
            'jumlah' => 2,
            'total' => '200.000',
            'keterangan' => 'Test transaction',
            'is_complete' => 1
        ];
        $response = $this->post(route('transaksis.store'), $transaksiData);
        // dd($response);
        $this->assertDatabaseHas('activity_log', [
            'event' => 'add_transaksi',
            'description' => 'User pawonkoe add a transaksi'
        ]);
    }
    public function test_create_data_log_activities_when_successful_edit_transaksi()
    {
        // Authenticate the user
        $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        // Prepare data for updating the transaction
        $transaksiDataEdit = [
            'is_complete' => 1
        ];

        // Update the transaction using the ID
        $responseEdit = $this->put(route('transaksis.update', 1), $transaksiDataEdit);

        // Check if the activity log was created
        $this->assertDatabaseHas('activity_log', [
            'event' => 'update_transaksi',
            'description' => 'User pawonkoe update a transaksi'
        ]);
    }
    public function test_create_data_log_activities_when_succesfull_create_preoder()
    {
        // / Authenticate the user
        $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        // Prepare the data for creating a transaction
        $transaksiData = [
            'tanggal' => now()->format('Y-m-d'),
            'product' => 1,
            'methode_pembayaran' => 1,
            'jumlah' => 2,
            'total' => '200.000',
            'nama' => 'John Doe',
            'email' => 'john@example.com',
            'alamat' => '123 Main St',
            'telepon' => '081234567890',
            'tanggal_dp' => now()->format('Y-m-d'),
            'jumlah_dp' => '50000',
        ];

        // Make the POST request to create a transaction
        $response = $this->post(route('transaksis.store'), $transaksiData);

        // Assert that the transaction is in the database
        $this->assertDatabaseHas('activity_log', [
            'event' => 'add_transaksi preorder',
            'description' => 'User pawonkoe add a transaksi preorder'
        ]);
    }

    public function test_create_data_log_activities_when_succesfull_edit_preoder()
    {
        // Create an initial transaction (or you could use a factory)
        $transaksi = Transaksi::create([
            'tanggal' => now()->format('Y-m-d'),
            'pembeli_id' => 1,
            'product_id' => 1,
            'methode_pembayaran_id' => 1,
            'jumlah' => 2,
            'total_harga' => '200000',
            'keterangan' => 'Initial transaction',
            'is_Preorder' => 1,
            'is_complete' => 0,
        ]);

        // Prepare the data for updating the transaction
        $updateData = [
            'is_complete' => 1,
            'jumlah_dp' => '50.000',
            'telepon' => '081234567890',
            'keterangan' => 'Updated transaction',
        ];

        // Make the PUT request to update the transaction
        $response = $this->put(route('transaksis.update', $transaksi->id), $updateData);

        // Assert that the response is a redirect
        // $response->assertRedirect(route('preorders.index'));
        // Assert that the success message is in the session
        // $this->assertSessionHas('success', 'Preorder has been updated successfully');
        // Assert that the transaction is updated in the database
        $this->assertDatabaseHas('activity_log', [
            'event' => 'update_transaksi preorder',
            'description' => 'User pawonkoe update a transaksi preorder'
        ]);
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

        $this->assertDatabaseHas('activity_log', [
            'event' => 'add_piutang',
            'description' => 'User pawonkoe add a piutang'
        ]);
    }

    public function test_create_data_log_activities_when_succesfull_edit_piutang()
    {
        $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        $piutang = Piutang::factory()->create([
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
        ]);

        $data = [
            'is_complete' => 1,
        ];

        $response = $this->put(route('piutang.update', $piutang->id), $data);

        $response->assertRedirect(route('piutang.index'));

        $this->assertDatabaseHas('activity_log', [
            'event' => 'update_piutang',
            'description' => 'User pawonkoe update a piutang'
        ]);
    }

    public function test_create_data_log_activities_when_succesfull_delete_piutang()
    {
        $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        $piutang = Piutang::factory()->create();

        $response = $this->delete(route('piutang.destroy', $piutang->id));

        $response->assertRedirect(route('piutang.index'));
        $this->assertDeleted($piutang);

        $this->assertCount(1, activity()->logs());
        $this->assertEquals('delete_piutang', activity()->logs()[0]->event);
    }
    public function test_create_data_log_activities_when_succesfull_create_hutang()
    {
        $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        $data = [
            'nama' => 'Ariq',
            'catatan' => 'Test catatan',
            'jumlahHutang' => 200000,
            'tenggat_waktu' => null,
            'tanggal_lunas' => null,
            'nominal' => 100000,
            'status' => 0,
        ];

        $this->assertDatabaseHas('activity_log', [
            'event' => 'create_hutang',
            'description' => 'User pawonkoe create a hutang'
        ]);
    }
    public function test_create_data_log_activities_when_succesfull_edit_hutang()
    {
        $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        $hutang = Hutang::factory()->create([
            'nama' => 'Ariq',
            'jumlah_hutang' => 200000,
            'status' => 0,
        ]);

        $data = [
            'nama' => 'Ariq Updated',
            'catatan' => 'Updated catatan',
            'jumlahHutang' => 300000,
            'tenggat_waktu' => null,
            'tanggal_lunas' => null,
            'status' => 0,
        ];

        $response = $this->put(route('hutang.update', $hutang->id), $data);

        $this->assertDatabaseHas('activity_log', [
            'event' => 'edit_hutang',
            'description' => 'User pawonkoe edit a hutang'
        ]);
    }
    public function test_create_data_log_activities_when_succesfull_delete_hutang()
    {
        $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        $hutang = Hutang::factory()->create();

        $response = $this->delete(route('hutang.destroy', $hutang->id));

        $response->assertRedirect(route('hutang.index'));
        $this->assertSessionHas('success', 'Data Berhasil Didelete');

        $this->assertDatabaseMissing('hutangs', [
            'id' => $hutang->id,
        ]);
        $this->assertDatabaseHas('activity_log', [
            'event' => 'delete_hutang',
            'description' => 'User pawonkoe delete a hutang'
        ]);
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
        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'default',
            'description' => 'User pawonkoe add a new beban kewajiban',
        ]);

    }
    public function test_create_data_log_activities_when_succesfull_edit_beban_kewajiban()
    {
        $bebanKewajiban = BebanKewajiban::factory()->create();
        $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        $data = [
            'jenis' => 'Updated Jenis',
            'nama' => 'Updated Nama',
            'nominal' => 2000,
            'tanggal' => now()->format('Y-m-d'),
        ];

        $response = $this->put(route('beban-kewajibans.update', $bebanKewajiban), $data);

        $this->assertDatabaseHas('beban_kewajibans', $data);
        $this->assertDatabaseHas('rekaps', [
            'keterangan' => 'Pembayaran Updated Jenis untuk Updated Nama',
            'jumlah' => 2000,
        ]);
        // Assert that the activity was logged
        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'default',
            'description' => 'User pawonkoe add a update a beban kewajiban',
        ]);

    }
    public function test_create_data_log_activities_when_succesfull_delete_beban_kewajiban()
    {
        $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        $bebanKewajiban = BebanKewajiban::factory()->create();

        $response = $this->delete(route('beban-kewajibans.destroy', $bebanKewajiban));

        $this->assertDeleted($bebanKewajiban);
        $this->assertDeleted('rekaps', ['id_tabel_asal' => $bebanKewajiban->id]);

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'default',
            'description' => 'User pawonkoe add a delete a beban kewajiban',
        ]);
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
    $this->assertDatabaseHas('activity_log', [
        'log_name' => 'default',
        'description' => 'User pawonkoe add a produksi',
    ]);
}

public function test_create_data_log_activities_when_successful_edit_produksi()
{
    // Authenticate the user
    $this->post(route('authentication'), [
        'nama' => 'pawonkoe',
        'password' => 'pawonkoe',
    ]);

    // Create a Produksi to edit
    $produksi = Produksi::factory()->create([
        'produk' => 'Old Product',
        'volume' => 8.0,
        'jumlah' => 2,
        'tanggal' => now()->format('Y-m-d'),
    ]);

    // Update the Produksi
    $data = [
        'produk' => 'Updated Product',
        'volume' => 12.0,
        'jumlah' => 3,
        'tanggal' => now()->format('Y-m-d'),
    ];
    $this->put(route('produksi.update', $produksi), $data);

    // Assert that the log activity has been created
    $this->assertDatabaseHas('activity_log', [
        'log_name' => 'default',
        'description' => 'User pawonkoe update a produksi',
    ]);
}

public function test_create_data_log_activities_when_successful_delete_produksi()
{
    // Authenticate the user
    $this->post(route('authentication'), [
        'nama' => 'pawonkoe',
        'password' => 'pawonkoe',
    ]);

    // Create a Produksi to delete
    $produksi = Produksi::factory()->create();

    // Delete the Produksi
    $this->delete(route('produksi.destroy', $produksi));

    // Assert that the log activity has been created
    $this->assertDatabaseHas('activity_log', [
        'log_name' => 'default',
        'description' => 'User pawonkoe delete a produksi',
    ]);
}

}
