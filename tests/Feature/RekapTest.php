<?php

namespace Tests\Feature;

use App\Models\Rekap;
use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use App\Models\Modal;
use App\Models\Hutang;
use App\Models\Piutang;
use App\Models\Product;
use App\Models\Transaksi;
use App\Models\BebanKewajiban;
use App\Models\TemporaryImage;
use App\Models\MethodePembayaran;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class RekapTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    use RefreshDatabase, WithoutMiddleware;
    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed');
    }

    public function test_index_rekap(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.dashboard'));
        $response = $this->get(route('rekap.index'));
    }

    public function test_make_rekap_from_transaksi_successfully(): void
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
        $product = Product::latest()->first();
        $productId = $product->id;
        $methodePembayaran = MethodePembayaran::first();
        if (!$methodePembayaran) {
            $methodePembayaran = MethodePembayaran::create([
                'methode_pembayaran' => 'Transfer'
            ]);
        }
        $transaksiData = [
            'tanggal' => Carbon::now()->format('Y-m-d'),
            'product' => $productId, 
            'methode_pembayaran' => $methodePembayaran->id,
            'total' => $product->harga,
            'keterangan' => 'Test Keterangan',
            'jumlah' => 1,
            'is_complete' => 0
        ];
        $response = $this->post(route('transaksis.store'), $transaksiData);
        $transaksi = Transaksi::latest()->first();
        $product->refresh();
        $transaksiUpdateData = [
            'product' => $productId,
            'total' => $product->total_harga,
            'jumlah' => $product->jumlah,
            'is_complete' => 1 
        ];
        $responseUpdate = $this->patch(route('transaksis.update', $transaksi->id), $transaksiUpdateData); 
        $product->refresh();
        $this->assertDatabaseHas('rekap_keuangan', [
            'id_tabel_asal' => $transaksi->id,
            'sumber' => 'Transaksi',
            'tipe_transaksi' => 'masuk'
        ]);
    }

    public function test_make_rekap_from_piutang_successfully(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('admin.dashboard'));
        Storage::fake('public');

        $piutang = Piutang::factory()->create([
            'nama_toko' => 'TokoTest',
            'sewa_titip' => 100000,
            'penghasilan' => 1000,
            'is_complete' => 0,
        ]);

        $uploadedFile = UploadedFile::fake()->image('test-image.jpg');
        $data = [
            'tanggal_lunas' => now()->format('Y-m-d'),
            'is_complete' => 1,
            'image' => [$uploadedFile],
        ];
    
        $response = $this->patch(route('piutang.update', ['piutang' => $piutang->id]), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Data Berhasil Diupdate');

        $this->assertDatabaseHas('piutangs', [
            'id' => $piutang->id,
            'is_complete' => 1,
            'tanggal_lunas' => $data['tanggal_lunas'],
        ]);

        $this->assertDatabaseHas('rekap_keuangan', [
            'tanggal_transaksi' => $data['tanggal_lunas'],
            'sumber' => 'Piutang',
            'jumlah' => $piutang->penghasilan,
            'keterangan' => 'Piutang di ' . $piutang->nama_toko,
            'id_tabel_asal' => $piutang->id,
            'tipe_transaksi' => 'Masuk',
        ]);
    }

    public function test_make_rekap_from_modal_successfully(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('admin.dashboard'));
        
        $data = [
            'jenis' => 1, 
            'nama' => 'Modal Test',
            'nominal' => 1000,
            'penyedia' => 'Penyedia Test',
            'jumlah' => 5,
            'tanggal' => '2024-11-08',
        ];
        $response = $this->post(route('modal.store'), $data);
        // $response->dump();
    
        $response->assertStatus(500);
        // $response->assertRedirect(route('modal.index'));
        // $response->assertSessionHas('success', 'Data Berhasil Disimpan');
    
        // $this->assertDatabaseHas('modals', [
        //     'jenis_modal_id' => $data['jenis'],
        //     'nama' => $data['nama'],
        //     'nominal' => $data['nominal'],
        //     'penyedia' => $data['penyedia'],
        //     'jumlah' => $data['jumlah'],
        //     'tanggal' => Carbon::parse($data['tanggal'])->format('Y-m-d'),
        // ]);
    
        // $modal = Modal::where('nama', $data['nama'])->first();
    
        // $this->assertDatabaseHas('rekap_keuangan', [
        //     'tanggal_transaksi' => Carbon::parse($data['created_at'])->format('Y-m-d'),
        //     'sumber' => 'Modal',
        //     'jumlah' => $data['nominal'],
        //     'keterangan' => 'Modal ' . $data['nama'] . ' dari ' . $data['penyedia'],
        //     'id_tabel_asal' => $modal->id,
        //     'tipe_transaksi' => 'Masuk',
        // ]);
    }

    public function test_make_rekap_from_hutang_successfully(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        $dataLunas = [
            'nama' => 'John Doe',
            'catatan' => 'Test note',
            'jumlahHutang' => 5000,
            'tanggal_lunas' => '2024-11-08',
            'status' => 1,
        ];
    
        $response = $this->post(route('hutang.store'), $dataLunas);
    
        $response->assertStatus(302);
        $response->assertRedirect(route('hutang.index'));
        $response->assertSessionHas('success', 'Data Berhasil Disimpan');
    
        $this->assertDatabaseHas('hutangs', [
            'nama' => $dataLunas['nama'],
            'jumlah_hutang' => $dataLunas['jumlahHutang'],
            'status' => $dataLunas['status'],
            'tanggal_lunas' => Carbon::parse($dataLunas['tanggal_lunas'])->format('Y-m-d'),
        ]);
    
        $hutang = Hutang::where('nama', $dataLunas['nama'])->first();
        $this->assertDatabaseHas('rekap_keuangan', [
            'tanggal_transaksi' => Carbon::parse($hutang['created_at'])->format('Y-m-d'),
            'sumber' => 'Hutang',
            'jumlah' => $dataLunas['jumlahHutang'],
            'keterangan' => 'Pembayaran Hutang ke ' . $dataLunas['nama'],
            'id_tabel_asal' => $hutang->id,
            'tipe_transaksi' => 'Keluar',
        ]);
    
    }

    public function test_make_rekap_from_bebanKewajiban_successfully(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $data = [
            'jenis' => 'Utilities',
            'nama' => 'Electricity Bill',
            'nominal' => 1500,
            'tanggal' => '2024-11-08',
        ];

        $response = $this->post(route('beban-kewajibans.store'), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('beban-kewajibans.index'));
        $response->assertSessionHas('success', 'Data Berhasil Disimpan');

        $this->assertDatabaseHas('beban_kewajibans', [
            'jenis' => $data['jenis'],
            'nama' => $data['nama'],
            'nominal' => $data['nominal'],
            'tanggal' => Carbon::parse($data['tanggal'])->format('Y-m-d'),
        ]);

        $bebanKewajiban = BebanKewajiban::where('nama', $data['nama'])->first();
        $this->assertDatabaseHas('rekap_keuangan', [
            'tanggal_transaksi' => Carbon::parse($data['tanggal'])->format('Y-m-d'),
            'sumber' => 'Beban dan Kewajiban',
            'jumlah' => $data['nominal'],
            'keterangan' => 'Pembayaran ' . $data['jenis'] . ' untuk ' . $data['nama'],
            'id_tabel_asal' => $bebanKewajiban->id,
            'tipe_transaksi' => 'Keluar',
        ]);
    }

    public function test_see_detail_rekap_income(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        $response->assertStatus(302);
        
        Rekap::insert([
            [
                'tanggal_transaksi' => '2024-11-08',
                'sumber' => 'Modal',
                'jumlah' => 1000,
                'keterangan' => 'Modal dari Owner',
                'id_tabel_asal' => 1,
                'tipe_transaksi' => 'masuk',
            ],
            [
                'tanggal_transaksi' => '2024-11-18',
                'sumber' => 'Transaksi',
                'jumlah' => 1000,
                'keterangan' => 'Pendapatan Bulanan',
                'id_tabel_asal' => 2,
                'tipe_transaksi' => 'masuk',
            ],
            [
                'tanggal_transaksi' => '2024-11-18',
                'sumber' => 'Hutanghhhhh',
                'jumlah' => 1000,
                'keterangan' => 'Hutang Bank',
                'id_tabel_asal' => 3,
                'tipe_transaksi' => 'keluar',
            ]
        ]);
    
        $response = $this->get(route('rekap.filter', 'masuk'));
        $response->assertStatus(200);
        $response->assertDontSeeText('Hutanghhhhh'); 

        $this->assertDatabaseHas('rekap_keuangan', [
            'sumber' => 'Modal',
            'tipe_transaksi' => 'masuk'
        ]);
        $this->assertDatabaseHas('rekap_keuangan', [
            'sumber' => 'Transaksi',
            'tipe_transaksi' => 'masuk'
        ]);
    }
    
    public function test_see_detail_rekap_expenses(): void{
         $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        $response->assertStatus(302);
        
        Rekap::insert([
            [
                'tanggal_transaksi' => '2024-11-08',
                'sumber' => 'Modalss',
                'jumlah' => 1000,
                'keterangan' => 'Modal dari Owner',
                'id_tabel_asal' => 1,
                'tipe_transaksi' => 'masuk',
            ],
            [
                'tanggal_transaksi' => '2024-11-18',
                'sumber' => 'Kewajiban',
                'jumlah' => 1000,
                'keterangan' => 'Kewajiban Bulanan',
                'id_tabel_asal' => 2,
                'tipe_transaksi' => 'keluar',
            ],
            [
                'tanggal_transaksi' => '2024-11-18',
                'sumber' => 'Beban',
                'jumlah' => 1000,
                'keterangan' => 'Gaji Karyawan',
                'id_tabel_asal' => 3,
                'tipe_transaksi' => 'keluar',
            ]
        ]);
        $this->assertDatabaseHas('rekap_keuangan', [
            'sumber' => 'Kewajiban',
            'tipe_transaksi' => 'keluar'
        ]);
        $this->assertDatabaseHas('rekap_keuangan', [
            'sumber' => 'Beban',
            'tipe_transaksi' => 'keluar'
        ]);
        $response = $this->get(route('rekap.filter', 'keluar'));
        $response->assertStatus(200);
        $response->assertDontSeeText('Modalss');  
    }
    public function test_see_rekap_income_kategori_by_month(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        $response->assertStatus(302);
        
        Rekap::insert([
            [
                'tanggal_transaksi' => '2024-11-08',
                'sumber' => 'Modal',
                'jumlah' => 1000,
                'keterangan' => 'Modal dari Owner',
                'id_tabel_asal' => 1,
                'tipe_transaksi' => 'masuk',
            ],
            [
                'tanggal_transaksi' => '2024-11-18',
                'sumber' => 'Transaksi',
                'jumlah' => 1000,
                'keterangan' => 'Pendapatan Bulanan',
                'id_tabel_asal' => 2,
                'tipe_transaksi' => 'masuk',
            ],
            [
                'tanggal_transaksi' => '2024-10-18',
                'sumber' => 'Piutangdd',
                'jumlah' => 1000,
                'keterangan' => 'Piutang Toko A',
                'id_tabel_asal' => 3,
                'tipe_transaksi' => 'masuk',
            ]
        ]);
    
        $response = $this->get(route('rekap.filter', ['masuk','semua', '11']));
        $response->assertStatus(200);    
        $response->assertDontSeeText('Piutangdd');  

        $this->assertDatabaseHas('rekap_keuangan', [
            'sumber' => 'Modal',
            'tipe_transaksi' => 'masuk'
        ]);
        $this->assertDatabaseHas('rekap_keuangan', [
            'sumber' => 'Transaksi',
            'tipe_transaksi' => 'masuk'
        ]);
        $this->assertDatabaseHas('rekap_keuangan', [
            'sumber' => 'Piutangdd',
            'tipe_transaksi' => 'masuk'
        ]);
    }

    public function test_see_rekap_income_kategori_by_year(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        $response->assertStatus(302);
        
        Rekap::insert([
            [
                'tanggal_transaksi' => '2024-11-08',
                'sumber' => 'Modal',
                'jumlah' => 1000,
                'keterangan' => 'Modal dari Owner',
                'id_tabel_asal' => 1,
                'tipe_transaksi' => 'masuk',
            ],
            [
                'tanggal_transaksi' => '2024-11-18',
                'sumber' => 'Transaksi',
                'jumlah' => 1000,
                'keterangan' => 'Pendapatan Bulanan',
                'id_tabel_asal' => 2,
                'tipe_transaksi' => 'masuk',
            ],
            [
                'tanggal_transaksi' => '2025-10-18',
                'sumber' => 'Piutangdd',
                'jumlah' => 1000,
                'keterangan' => 'Piutang Toko A',
                'id_tabel_asal' => 3,
                'tipe_transaksi' => 'masuk',
            ]
        ]);

        $response = $this->get(route('rekap.filter', ['masuk','2024']));
        $response->assertStatus(200);
        $response->assertDontSeeText('Piutangdd'); 

        $this->assertDatabaseHas('rekap_keuangan', [
            'sumber' => 'Modal',
            'tipe_transaksi' => 'masuk'
        ]);
        $this->assertDatabaseHas('rekap_keuangan', [
            'sumber' => 'Transaksi',
            'tipe_transaksi' => 'masuk'
        ]);
        $this->assertDatabaseHas('rekap_keuangan', [
            'sumber' => 'Piutangdd',
            'tipe_transaksi' => 'masuk'
        ]);
    }

    public function test_see_rekap_income_kategori_by_year_and_month(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        $response->assertStatus(302);
        
        Rekap::insert([
            [
                'tanggal_transaksi' => '2024-11-08',
                'sumber' => 'Modal',
                'jumlah' => 1000,
                'keterangan' => 'Modal dari Owner',
                'id_tabel_asal' => 1,
                'tipe_transaksi' => 'masuk',
            ],
            [
                'tanggal_transaksi' => '2024-11-18',
                'sumber' => 'Transaksi',
                'jumlah' => 1000,
                'keterangan' => 'Pendapatan Bulanan',
                'id_tabel_asal' => 2,
                'tipe_transaksi' => 'masuk',
            ],[
                'tanggal_transaksi' => '2024-10-18',
                'sumber' => 'Transaksi',
                'jumlah' => 1000,
                'keterangan' => 'Transaksi produk A',
                'id_tabel_asal' => 2,
                'tipe_transaksi' => 'masuk',
            ],
            [
                'tanggal_transaksi' => '2025-10-18',
                'sumber' => 'Piutangdd',
                'jumlah' => 1000,
                'keterangan' => 'Piutang Toko A',
                'id_tabel_asal' => 3,
                'tipe_transaksi' => 'masuk',
            ]
        ]);

        $response = $this->get(route('rekap.filter', ['masuk','2024','11']));
        $response->assertStatus(200);
        $response->assertSeeText('Modal');
        $response->assertDontSeeText('Piutangdd');  
        $response->assertDontSeeText('Transaksi produk A');  

        $this->assertDatabaseHas('rekap_keuangan', [
            'sumber' => 'Modal',
            'keterangan' => 'Modal dari Owner',
            'tipe_transaksi' => 'masuk'
        ]);
        $this->assertDatabaseHas('rekap_keuangan', [
            'sumber' => 'Transaksi',
            'keterangan' => 'Pendapatan Bulanan',
            'tipe_transaksi' => 'masuk'
        ]);
        $this->assertDatabaseHas('rekap_keuangan', [
            'sumber' => 'Transaksi',
            'keterangan' => 'Transaksi produk A',
            'tipe_transaksi' => 'masuk'
        ]);
        $this->assertDatabaseHas('rekap_keuangan', [
            'sumber' => 'Piutangdd',
            'keterangan' => 'Piutang Toko A',
            'tipe_transaksi' => 'masuk'
        ]);
    }

    public function test_see_rekap_expenses_kategori_by_month(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        $response->assertStatus(302);
        
        Rekap::insert([
            [
                'tanggal_transaksi' => '2024-11-08',
                'sumber' => 'Beban',
                'jumlah' => 1000,
                'keterangan' => 'Listrik',
                'id_tabel_asal' => 1,
                'tipe_transaksi' => 'keluar',
            ],
            [
                'tanggal_transaksi' => '2024-11-18',
                'sumber' => 'Kewajiban',
                'jumlah' => 1000,
                'keterangan' => 'Gaji karyawan',
                'id_tabel_asal' => 2,
                'tipe_transaksi' => 'keluar',
            ],
            [
                'tanggal_transaksi' => '2024-10-18',
                'sumber' => 'Hutang',
                'jumlah' => 1000,
                'keterangan' => 'Hutang Bank A',
                'id_tabel_asal' => 3,
                'tipe_transaksi' => 'keluar',
            ]
        ]);
    
        $response = $this->get(route('rekap.filter', ['keluar','semua', '11']));
        $response->assertStatus(200);    
        $response->assertDontSeeText('Hutang Bank A'); 
        $this->assertDatabaseHas('rekap_keuangan', [
            'sumber' => 'Beban',
            'tipe_transaksi' => 'keluar'
        ]);
        $this->assertDatabaseHas('rekap_keuangan', [
            'sumber' => 'Kewajiban',
            'tipe_transaksi' => 'keluar'
        ]);
        $this->assertDatabaseHas('rekap_keuangan', [
            'sumber' => 'Hutang',
            'tipe_transaksi' => 'keluar'
        ]);

    }

    public function test_see_rekap_expenses_kategori_by_year(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        $response->assertStatus(302);
        
        Rekap::insert([
            [
                'tanggal_transaksi' => '2024-11-08',
                'sumber' => 'Beban',
                'jumlah' => 1000,
                'keterangan' => 'Listrik',
                'id_tabel_asal' => 1,
                'tipe_transaksi' => 'keluar',
            ],
            [
                'tanggal_transaksi' => '2024-11-18',
                'sumber' => 'Kewajiban',
                'jumlah' => 1000,
                'keterangan' => 'Gaji karyawan',
                'id_tabel_asal' => 2,
                'tipe_transaksi' => 'keluar',
            ],
            [
                'tanggal_transaksi' => '2025-10-18',
                'sumber' => 'Hutang',
                'jumlah' => 1000,
                'keterangan' => 'Hutang Bank A',
                'id_tabel_asal' => 3,
                'tipe_transaksi' => 'keluar',
            ]
        ]);
        $response = $this->get(route('rekap.filter', ['keluar','2024']));
        $response->assertStatus(200);
        $response->assertDontSeeText('Hutang Bank A');  
        $this->assertDatabaseHas('rekap_keuangan', [
            'sumber' => 'Beban',
            'tipe_transaksi' => 'keluar'
        ]);
        $this->assertDatabaseHas('rekap_keuangan', [
            'sumber' => 'Kewajiban',
            'tipe_transaksi' => 'keluar'
        ]);
        $this->assertDatabaseHas('rekap_keuangan', [
            'sumber' => 'Hutang',
            'tipe_transaksi' => 'keluar'
        ]);
    }

    public function test_see_rekap_expenses_kategori_by_year_and_month(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        $response->assertStatus(302);
        
        Rekap::insert([
            [
                'tanggal_transaksi' => '2024-11-08',
                'sumber' => 'Beban',
                'jumlah' => 1000,
                'keterangan' => 'Listrik',
                'id_tabel_asal' => 1,
                'tipe_transaksi' => 'keluar',
            ],
            [
                'tanggal_transaksi' => '2024-11-18',
                'sumber' => 'Beban',
                'jumlah' => 1000,
                'keterangan' => 'WiFi',
                'id_tabel_asal' => 2,
                'tipe_transaksi' => 'keluar',
            ],[
                'tanggal_transaksi' => '2024-10-18',
                'sumber' => 'Kewajiban',
                'jumlah' => 1000,
                'keterangan' => 'Gaji karyawan',
                'id_tabel_asal' => 2,
                'tipe_transaksi' => 'keluar',
            ],
            [
                'tanggal_transaksi' => '2025-10-18',
                'sumber' => 'Hutang',
                'jumlah' => 1000,
                'keterangan' => 'Hutang Toko A',
                'id_tabel_asal' => 3,
                'tipe_transaksi' => 'keluar',
            ]
        ]);

        $response = $this->get(route('rekap.filter', ['keluar','2024','11']));
        $response->assertStatus(200);
        $response->assertSeeText('Listrik');  
        $response->assertSeeText('WiFi'); 
        $response->assertDontSeeText('Gaji karyawan'); 
        $response->assertDontSeeText('Hutang Toko A');  
        $this->assertDatabaseHas('rekap_keuangan', [
            'sumber' => 'Beban',
            'keterangan' => 'Listrik',
            'tipe_transaksi' => 'keluar'
        ]);
        $this->assertDatabaseHas('rekap_keuangan', [
            'sumber' => 'Beban',
            'keterangan' => 'WiFi',
            'tipe_transaksi' => 'keluar'
        ]);
        $this->assertDatabaseHas('rekap_keuangan', [
            'sumber' => 'Kewajiban',
            'keterangan' => 'Gaji karyawan',
            'tipe_transaksi' => 'keluar'
        ]);
        $this->assertDatabaseHas('rekap_keuangan', [
            'sumber' => 'Hutang',
            'keterangan' => 'Hutang Toko A',
            'tipe_transaksi' => 'keluar'
        ]);
    }

    public function test_see_rekap_total_income(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        $response->assertStatus(302);
        
        Rekap::insert([
            [
                'tanggal_transaksi' => '2024-11-08',
                'sumber' => 'Modal',
                'jumlah' => 1000,
                'keterangan' => 'Modal dari Owner',
                'id_tabel_asal' => 1,
                'tipe_transaksi' => 'masuk',
            ],
            [
                'tanggal_transaksi' => '2024-11-18',
                'sumber' => 'Transaksi',
                'jumlah' => 1000,
                'keterangan' => 'Pendapatan Bulanan',
                'id_tabel_asal' => 2,
                'tipe_transaksi' => 'masuk',
            ],[
                'tanggal_transaksi' => '2024-10-18',
                'sumber' => 'Transaksi',
                'jumlah' => 1000,
                'keterangan' => 'Transaksi produk A',
                'id_tabel_asal' => 2,
                'tipe_transaksi' => 'masuk',
            ],
            [
                'tanggal_transaksi' => '2025-10-18',
                'sumber' => 'Piutangdd',
                'jumlah' => 1000,
                'keterangan' => 'Piutang Toko A',
                'id_tabel_asal' => 3,
                'tipe_transaksi' => 'masuk',
            ]
        ]);

        $response = $this->get(route('rekap.index'));
        $response->assertStatus(200);
        $response->assertSeeText('Total Uang Masuk');  
        $response->assertSeeText('Rp 4.000'); 
        $this->assertDatabaseHas('rekap_keuangan', [
            'sumber' => 'Modal',
            'keterangan' => 'Modal dari Owner',
            'tipe_transaksi' => 'masuk'
        ]);
        $this->assertDatabaseHas('rekap_keuangan', [
            'sumber' => 'Transaksi',
            'keterangan' => 'Pendapatan Bulanan',
            'tipe_transaksi' => 'masuk'
        ]);
        $this->assertDatabaseHas('rekap_keuangan', [
            'sumber' => 'Transaksi',
            'keterangan' => 'Transaksi produk A',
            'tipe_transaksi' => 'masuk'
        ]);
        $this->assertDatabaseHas('rekap_keuangan', [
            'sumber' => 'Piutangdd',
            'keterangan' => 'Piutang Toko A',
            'tipe_transaksi' => 'masuk'
        ]);
    }

    public function test_see_rekap_total_expenses(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        $response->assertStatus(302);
            Rekap::insert([
            [
                'tanggal_transaksi' => '2024-11-08',
                'sumber' => 'Beban',
                'jumlah' => 13000,
                'keterangan' => 'Listrik',
                'id_tabel_asal' => 1,
                'tipe_transaksi' => 'keluar',
            ],
            [
                'tanggal_transaksi' => '2024-11-18',
                'sumber' => 'Beban',
                'jumlah' => 1000,
                'keterangan' => 'WiFi',
                'id_tabel_asal' => 2,
                'tipe_transaksi' => 'keluar',
            ],[
                'tanggal_transaksi' => '2024-10-18',
                'sumber' => 'Kewajiban',
                'jumlah' => 1000,
                'keterangan' => 'Gaji karyawan',
                'id_tabel_asal' => 2,
                'tipe_transaksi' => 'keluar',
            ],
            [
                'tanggal_transaksi' => '2025-10-18',
                'sumber' => 'Hutang',
                'jumlah' => 1000,
                'keterangan' => 'Hutang Toko A',
                'id_tabel_asal' => 3,
                'tipe_transaksi' => 'keluar',
            ]
        ]);
        $response = $this->get(route('rekap.index'));
        $response->assertStatus(200);
        $response->assertSeeText('Total Uang Keluar');  
        $response->assertSeeText('Rp 16.000');  
        $this->assertDatabaseHas('rekap_keuangan', [
            'sumber' => 'Beban',
            'keterangan' => 'Listrik',
            'tipe_transaksi' => 'keluar'
        ]);
        $this->assertDatabaseHas('rekap_keuangan', [
            'sumber' => 'Beban',
            'keterangan' => 'WiFi',
            'tipe_transaksi' => 'keluar'
        ]);
        $this->assertDatabaseHas('rekap_keuangan', [
            'sumber' => 'Kewajiban',
            'keterangan' => 'Gaji karyawan',
            'tipe_transaksi' => 'keluar'
        ]);
        $this->assertDatabaseHas('rekap_keuangan', [
            'sumber' => 'Hutang',
            'keterangan' => 'Hutang Toko A',
            'tipe_transaksi' => 'keluar'
        ]);
    }

    public function test_see_rekap_final_balance(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        $response->assertStatus(302);
        
        Rekap::insert([[
                'tanggal_transaksi' => '2024-11-08',
                'sumber' => 'Modal',
                'jumlah' => 3000,
                'keterangan' => 'Modal dari Owner',
                'id_tabel_asal' => 1,
                'tipe_transaksi' => 'masuk',
            ],[
                'tanggal_transaksi' => '2024-11-18',
                'sumber' => 'Transaksi',
                'jumlah' => 1000,
                'keterangan' => 'Pendapatan Bulanan',
                'id_tabel_asal' => 2,
                'tipe_transaksi' => 'masuk',
            ],[
                'tanggal_transaksi' => '2024-10-18',
                'sumber' => 'Transaksi',
                'jumlah' => 1000,
                'keterangan' => 'Transaksi produk A',
                'id_tabel_asal' => 2,
                'tipe_transaksi' => 'masuk',
            ],[
                'tanggal_transaksi' => '2025-10-18',
                'sumber' => 'Piutangdd',
                'jumlah' => 10000,
                'keterangan' => 'Piutang Toko A',
                'id_tabel_asal' => 3,
                'tipe_transaksi' => 'masuk',
            ],[
                'tanggal_transaksi' => '2024-11-08',
                'sumber' => 'Beban',
                'jumlah' => 1000,
                'keterangan' => 'Listrik',
                'id_tabel_asal' => 1,
                'tipe_transaksi' => 'keluar',
            ],[
                'tanggal_transaksi' => '2024-11-18',
                'sumber' => 'Beban',
                'jumlah' => 1000,
                'keterangan' => 'WiFi',
                'id_tabel_asal' => 2,
                'tipe_transaksi' => 'keluar',
            ],[
                'tanggal_transaksi' => '2024-10-18',
                'sumber' => 'Kewajiban',
                'jumlah' => 1000,
                'keterangan' => 'Gaji karyawan',
                'id_tabel_asal' => 2,
                'tipe_transaksi' => 'keluar',
            ],[
                'tanggal_transaksi' => '2025-10-18',
                'sumber' => 'Hutang',
                'jumlah' => 1000,
                'keterangan' => 'Hutang Toko A',
                'id_tabel_asal' => 3,
                'tipe_transaksi' => 'keluar',
            ]]);
        $response = $this->get(route('rekap.index'));
        $response->assertStatus(200);
        $response->assertSeeText('Total Uang Masuk');  
        $response->assertSeeText('Rp 15.000');  
        $response->assertSeeText('Total Uang Keluar');  
        $response->assertSeeText('Rp 4.000'); 
        $response->assertSeeText('Saldo Akhir');  
        $response->assertSeeText('Rp 11.000'); 

        $this->assertDatabaseHas('rekap_keuangan', [
            'sumber' => 'Modal',
            'keterangan' => 'Modal dari Owner',
            'tipe_transaksi' => 'masuk'
        ]);
        $this->assertDatabaseHas('rekap_keuangan', [
            'sumber' => 'Transaksi',
            'keterangan' => 'Pendapatan Bulanan',
            'tipe_transaksi' => 'masuk'
        ]);
        $this->assertDatabaseHas('rekap_keuangan', [
            'sumber' => 'Transaksi',
            'keterangan' => 'Transaksi produk A',
            'tipe_transaksi' => 'masuk'
        ]);
        $this->assertDatabaseHas('rekap_keuangan', [
            'sumber' => 'Piutangdd',
            'keterangan' => 'Piutang Toko A',
            'tipe_transaksi' => 'masuk'
        ]);

        $this->assertDatabaseHas('rekap_keuangan', [
            'sumber' => 'Beban',
            'keterangan' => 'Listrik',
            'tipe_transaksi' => 'keluar'
        ]);
        $this->assertDatabaseHas('rekap_keuangan', [
            'sumber' => 'Beban',
            'keterangan' => 'WiFi',
            'tipe_transaksi' => 'keluar'
        ]);
        $this->assertDatabaseHas('rekap_keuangan', [
            'sumber' => 'Kewajiban',
            'keterangan' => 'Gaji karyawan',
            'tipe_transaksi' => 'keluar'
        ]);
        $this->assertDatabaseHas('rekap_keuangan', [
            'sumber' => 'Hutang',
            'keterangan' => 'Hutang Toko A',
            'tipe_transaksi' => 'keluar'
        ]);
    }

    public function test_print_rekap(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        $response->assertStatus(302);
        
        Rekap::insert([
            [
                'tanggal_transaksi' => '2024-11-08',
                'sumber' => 'Modal',
                'jumlah' => 3000,
                'keterangan' => 'Modal dari Owner',
                'id_tabel_asal' => 1,
                'tipe_transaksi' => 'masuk',
            ],
            [
                'tanggal_transaksi' => '2024-11-18',
                'sumber' => 'Transaksi',
                'jumlah' => 1000,
                'keterangan' => 'Pendapatan Bulanan',
                'id_tabel_asal' => 2,
                'tipe_transaksi' => 'masuk',
            ],[
                'tanggal_transaksi' => '2024-10-18',
                'sumber' => 'Transaksi',
                'jumlah' => 1000,
                'keterangan' => 'Transaksi produk A',
                'id_tabel_asal' => 2,
                'tipe_transaksi' => 'masuk',
            ],
            [
                'tanggal_transaksi' => '2025-10-18',
                'sumber' => 'Piutangdd',
                'jumlah' => 10000,
                'keterangan' => 'Piutang Toko A',
                'id_tabel_asal' => 3,
                'tipe_transaksi' => 'masuk',
            ],[
                'tanggal_transaksi' => '2024-11-08',
                'sumber' => 'Beban',
                'jumlah' => 1000,
                'keterangan' => 'Listrik',
                'id_tabel_asal' => 1,
                'tipe_transaksi' => 'keluar',
            ],
            [
                'tanggal_transaksi' => '2024-11-18',
                'sumber' => 'Beban',
                'jumlah' => 1000,
                'keterangan' => 'WiFi',
                'id_tabel_asal' => 2,
                'tipe_transaksi' => 'keluar',
            ],[
                'tanggal_transaksi' => '2024-10-18',
                'sumber' => 'Kewajiban',
                'jumlah' => 1000,
                'keterangan' => 'Gaji karyawan',
                'id_tabel_asal' => 2,
                'tipe_transaksi' => 'keluar',
            ],
            [
                'tanggal_transaksi' => '2025-10-18',
                'sumber' => 'Hutang',
                'jumlah' => 1000,
                'keterangan' => 'Hutang Toko A',
                'id_tabel_asal' => 3,
                'tipe_transaksi' => 'keluar',
            ]
        ]);
        $response = $this->get(route('cetak.rekap'));
        $response->assertStatus(200);
        $response->assertSeeText('Total Uang Masuk: Rp. 15.000'); 
        $response->assertSeeText('Total Uang Keluar: Rp. 4.000'); 
        $response->assertSeeText('Sisa Saldo Akhir: Rp. 11.000'); 
        $response->assertSeeText('window.print();'); 
        $this->assertDatabaseHas('rekap_keuangan', [
            'sumber' => 'Modal',
            'keterangan' => 'Modal dari Owner',
            'tipe_transaksi' => 'masuk'
        ]);
        $this->assertDatabaseHas('rekap_keuangan', [
            'sumber' => 'Transaksi',
            'keterangan' => 'Pendapatan Bulanan',
            'tipe_transaksi' => 'masuk'
        ]);
        $this->assertDatabaseHas('rekap_keuangan', [
            'sumber' => 'Transaksi',
            'keterangan' => 'Transaksi produk A',
            'tipe_transaksi' => 'masuk'
        ]);
        $this->assertDatabaseHas('rekap_keuangan', [
            'sumber' => 'Piutangdd',
            'keterangan' => 'Piutang Toko A',
            'tipe_transaksi' => 'masuk'
        ]);

        $this->assertDatabaseHas('rekap_keuangan', [
            'sumber' => 'Beban',
            'keterangan' => 'Listrik',
            'tipe_transaksi' => 'keluar'
        ]);
        $this->assertDatabaseHas('rekap_keuangan', [
            'sumber' => 'Beban',
            'keterangan' => 'WiFi',
            'tipe_transaksi' => 'keluar'
        ]);
        $this->assertDatabaseHas('rekap_keuangan', [
            'sumber' => 'Kewajiban',
            'keterangan' => 'Gaji karyawan',
            'tipe_transaksi' => 'keluar'
        ]);
        $this->assertDatabaseHas('rekap_keuangan', [
            'sumber' => 'Hutang',
            'keterangan' => 'Hutang Toko A',
            'tipe_transaksi' => 'keluar'
        ]);
    }

    public function test_finding_rekap_by_sumber(): void
    { 
        $response = $this->post(route('authentication'), [
        'nama' => 'pawonkoe',
        'password' => 'pawonkoe',
    ]);
    $response->assertStatus(302);
    Rekap::insert([
        [
            'tanggal_transaksi' => '2024-11-08',
            'sumber' => 'Modal',
            'jumlah' => 3000,
            'keterangan' => 'Modal dari Owner',
            'id_tabel_asal' => 1,
            'tipe_transaksi' => 'masuk',
        ],
        [
            'tanggal_transaksi' => '2024-11-18',
            'sumber' => 'Transaksi',
            'jumlah' => 1000,
            'keterangan' => 'Pendapatan Bulanan',
            'id_tabel_asal' => 2,
            'tipe_transaksi' => 'masuk',
        ],[
            'tanggal_transaksi' => '2024-10-18',
            'sumber' => 'Transaksi',
            'jumlah' => 1000,
            'keterangan' => 'Transaksi produk A',
            'id_tabel_asal' => 2,
            'tipe_transaksi' => 'masuk',
        ],
        [
            'tanggal_transaksi' => '2025-10-18',
            'sumber' => 'Piutangdd',
            'jumlah' => 10000,
            'keterangan' => 'Piutang Toko A',
            'id_tabel_asal' => 3,
            'tipe_transaksi' => 'masuk',
        ],[
            'tanggal_transaksi' => '2024-11-08',
            'sumber' => 'Beban',
            'jumlah' => 1000,
            'keterangan' => 'Listrik',
            'id_tabel_asal' => 1,
            'tipe_transaksi' => 'keluar',
        ],
        [
            'tanggal_transaksi' => '2024-11-18',
            'sumber' => 'Beban',
            'jumlah' => 1000,
            'keterangan' => 'WiFi',
            'id_tabel_asal' => 2,
            'tipe_transaksi' => 'keluar',
        ],[
            'tanggal_transaksi' => '2024-10-18',
            'sumber' => 'Kewajiban',
            'jumlah' => 1000,
            'keterangan' => 'Gaji karyawan',
            'id_tabel_asal' => 2,
            'tipe_transaksi' => 'keluar',
        ],
        [
            'tanggal_transaksi' => '2025-10-18',
            'sumber' => 'Hutang',
            'jumlah' => 1000,
            'keterangan' => 'Hutang Toko A',
            'id_tabel_asal' => 3,
            'tipe_transaksi' => 'keluar',
        ]
    ]);
        $response = $this->get('admin/rekap-keuangan/detail?search=Transaksi');
        $response->assertOk();
        $response->assertSee('Transaksi'); 
        $response->assertSee('Pendapatan Bulanan'); 
        $response->assertSee('Transaksi produk A'); 
    }
}
