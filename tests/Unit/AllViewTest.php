<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Modal;
use App\Models\Piutang;
use App\Models\Product;
use App\Models\Produksi;
use App\Models\Transaksi;
use App\Models\JenisModal;
use App\Models\BebanKewajiban;
use App\Models\TemporaryImage;
use App\Models\MethodePembayaran;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AllViewTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();

        // Refresh database and run all seeders
        $this->artisan('db:seed');
        $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
    }

    public function test_view_index_bebanKewajiban(){
        $response = $this->get(route('beban-kewajibans.index'));
        $response->assertStatus(200);
        $response->assertViewIs('pages.beban-kewajiban.index');

    }
    public function test_view_create_bebanKewajiban(){
        $response = $this->get(route('beban-kewajibans.create'));
        $response->assertStatus(200);
        $response->assertViewIs('pages.beban-kewajiban.create');
    }

    public function test_view_edit_bebanKewajiban(){

        $beban = BebanKewajiban::create([
            'jenis' => 'Test Jenis',
            'nama' => 'Test Nama',
            'nominal' => 1000,
            'tanggal' => now()->format('Y-m-d'),
        ]);
        $response = $this->get(route('beban-kewajibans.edit', ['bebanKewajiban' => $beban->id]));
        $response->assertStatus(200);
        $response->assertViewIs('pages.beban-kewajiban.edit');
    }

    public function test_view_index_Modal(){
        $response = $this->get(route('modal.index'));
        $response->assertStatus(200);
        $response->assertViewIs('pages.modal.index');
    }

    public function test_view_create_Modal(){
        $response = $this->get(route('modal.create'));
        $response->assertStatus(200);
        $response->assertViewIs('pages.modal.create');
    }

    public function test_view_edit_Modal(){
        $modalFisik = JenisModal::create([
            'jenis_modal' => 'Fisik',
        ]);

        // Create initial modal data
        $data = Modal::create([
            'jenis_modal_id' => $modalFisik->id, // Assuming valid `jenis_modal_id` exists
            'nama' => 'Test Modal',
            'nominal' => 5000,
            'penyedia' => 'Test Provider',
            'jumlah' => 10,
            'tanggal' => now()->format('Y-m-d'),
        ]);

        $response = $this->get(route('modal.edit', ['modal' => $data->id]));
        $response->assertStatus(200);
        $response->assertViewIs('pages.modal.edit');
    }

    public function test_view_index_piutang(){
        $response = $this->get(route('piutang.index'));
        $response->assertStatus(200);
        $response->assertViewIs('pages.piutang.index');
    }

    public function test_view_create_piutang(){
        $response = $this->get(route('piutang.create'));
        $response->assertStatus(200);
        $response->assertViewIs('pages.piutang.create');
    }

    public function test_view_edit_piutang(){
        $piutang = Piutang::create([
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
        $response = $this->get(route('piutang.edit', ['piutang' => $piutang->id]));
        $response->assertStatus(200);
        $response->assertViewIs('pages.piutang.edit');
    }

    public function test_view_index_produksi(){
        $response = $this->get(route('produksi.index'));
        $response->assertStatus(200);
        $response->assertViewIs('pages.produksi.index');
    }

    public function test_view_create_produksi(){
        $response = $this->get(route('produksi.create'));
        $response->assertStatus(200);
        $response->assertViewIs('pages.produksi.create');
    }

    public function test_view_edit_produksi(){

        $produksi = Produksi::create([
            'produk' => 'Produk Test',
            'volume' => 10.5,
            'jumlah' => 5,
            'tanggal' => now()->format('Y-m-d'),
        ]);

        $response = $this->get(route('produksi.edit', ['produksi' => $produksi->id]));
        $response->assertStatus(200);
        $response->assertViewIs('pages.produksi.edit');
    }

    public function test_view_index_product(){
        $response = $this->get(route('products.index'));
        $response->assertStatus(200);
        $response->assertViewIs('pages.admin.product.index');
    }

    public function test_view_create_product(){
        $response = $this->get(route('products.create'));
        $response->assertStatus(200);
        $response->assertViewIs('pages.admin.product.create');
    }

    public function test_view_edit_product(){

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
        $product = Product::where('nama_product', 'Test Product')->first();

        $response = $this->get(route('products.edit', ['product' => $product->id]));
        $response->assertStatus(200);
        $response->assertViewIs('pages.admin.product.edit');
    }

    public function test_view_detail_product(){

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
        $product = Product::where('nama_product', 'Test Product')->first();
        $methodePembayaran = MethodePembayaran::first();
        if (!$methodePembayaran) {
            $methodePembayaran = MethodePembayaran::create([
                'methode_pembayaran' => 'Transfer'
            ]);
        }
        $transaksiData = [
            'tanggal' => Carbon::now()->format('Y-m-d'),
            'product' => $product->id, // Pastikan ini product_id
            'methode_pembayaran' => $methodePembayaran->id,
            'total' => $product->harga,
            'keterangan' => 'Test Keterangan',
            'jumlah' => 5,
            'is_complete' => 1
        ];

        // Menyimpan transaksi
        $response = $this->post(route('transaksis.store'), $transaksiData);
        $response = $this->get(route('products.detail', ['product' => $product->id]));
        $response->assertStatus(200);
        $response->assertViewIs('pages.admin.product.detail');
    }
}
