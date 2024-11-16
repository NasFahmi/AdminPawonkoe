<?php

namespace Tests\Unit;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\TemporaryImage;
use Illuminate\Http\UploadedFile;
use Tests\TestCase; // Change t
use Storage;
class HomeTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // $this->withoutExceptionHandling();
        $this->artisan('db:seed');
    }
    public function test_indexImage_return_json_image_product()
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
        $productsData = [
            [
                'nama_product' => 'Sambal Kemangi',
                'slug' => 'Sambal-Kemangi',
                'harga' => '100000',
                'deskripsi' => 'Test Description',
                'link_shopee' => 'https://shopee.com/test',
                'stok' => '10',
                'tersedia' => '1',
                'spesifikasi_product' => 'Test Specifications',
                'images' => [json_encode([$temporaryFolder])],
                'varian' => ['Red', 'Blue']
            ],
            [
                'nama_product' => 'Sambal Cumi',
                'slug' => 'Sambal-Cumi',
                'harga' => '100000',
                'deskripsi' => 'Test Description',
                'link_shopee' => 'https://shopee.com/test',
                'stok' => '10',
                'tersedia' => '1',
                'spesifikasi_product' => 'Test Specifications',
                'images' => [json_encode([$temporaryFolder])],
                'varian' => ['Red', 'Blue']
            ],
            [
                'nama_product' => 'Iga Rendang',
                'slug' => 'Iga-Rendang',
                'harga' => '100000',
                'deskripsi' => 'Test Description',
                'link_shopee' => 'https://shopee.com/test',
                'stok' => '10',
                'tersedia' => '1',
                'spesifikasi_product' => 'Test Specifications',
                'images' => [json_encode([$temporaryFolder])],
                'varian' => ['Red', 'Blue']
            ]
        ];
        foreach ($productsData as $productData) {
            Product::create($productData); // Create each product individually
        }
        $response = $this->getJson('api/image/index');
        // Assert the response
        $response->assertStatus(200);
    }
    public function test_katalog_return_json_product_katalog()
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
        $productsData = [
            [
                'nama_product' => 'Sambal Kemangi',
                'slug' => 'Sambal-Kemangi',
                'harga' => '100000',
                'deskripsi' => 'Test Description',
                'link_shopee' => 'https://shopee.com/test',
                'stok' => '10',
                'tersedia' => '1',
                'spesifikasi_product' => 'Test Specifications',
                'images' => [json_encode([$temporaryFolder])],
                'varian' => ['Red', 'Blue']
            ],
            [
                'nama_product' => 'Sambal Cumi',
                'slug' => 'Sambal-Cumi',
                'harga' => '100000',
                'deskripsi' => 'Test Description',
                'link_shopee' => 'https://shopee.com/test',
                'stok' => '10',
                'tersedia' => '1',
                'spesifikasi_product' => 'Test Specifications',
                'images' => [json_encode([$temporaryFolder])],
                'varian' => ['Red', 'Blue']
            ],
            [
                'nama_product' => 'Iga Rendang',
                'slug' => 'Iga-Rendang',
                'harga' => '100000',
                'deskripsi' => 'Test Description',
                'link_shopee' => 'https://shopee.com/test',
                'stok' => '10',
                'tersedia' => '1',
                'spesifikasi_product' => 'Test Specifications',
                'images' => [json_encode([$temporaryFolder])],
                'varian' => ['Red', 'Blue']
            ]
        ];
        foreach ($productsData as $productData) {
            Product::create($productData); // Create each product individually
        }
        $response = $this->getJson('api/katalog');
        // Assert the response
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'title' => 'Sambal Kemangi',
            'description' => 'Test Description',
            'price' => "100000",
        ]);

    }

    public function test_guest_can_show_detail_product()
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
        $productsData = [
            [
                'nama_product' => 'Sambal Kemangi',
                'slug' => 'Sambal-Kemangi',
                'harga' => '100000',
                'deskripsi' => 'Test Description',
                'link_shopee' => 'https://shopee.com/test',
                'stok' => '10',
                'tersedia' => '1',
                'spesifikasi_product' => 'Test Specifications',
                'images' => [json_encode([$temporaryFolder])],
                'varian' => ['Red', 'Blue']
            ],
            [
                'nama_product' => 'Sambal Cumi',
                'slug' => 'Sambal-Cumi',
                'harga' => '100000',
                'deskripsi' => 'Test Description',
                'link_shopee' => 'https://shopee.com/test',
                'stok' => '10',
                'tersedia' => '1',
                'spesifikasi_product' => 'Test Specifications',
                'images' => [json_encode([$temporaryFolder])],
                'varian' => ['Red', 'Blue']
            ],
            [
                'nama_product' => 'Iga Rendang',
                'slug' => 'Iga-Rendang',
                'harga' => '100000',
                'deskripsi' => 'Test Description',
                'link_shopee' => 'https://shopee.com/test',
                'stok' => '10',
                'tersedia' => '1',
                'spesifikasi_product' => 'Test Specifications',
                'images' => [json_encode([$temporaryFolder])],
                'varian' => ['Red', 'Blue']
            ]
        ];
        foreach ($productsData as $productData) {
            Product::create($productData); // Create each product individually
        }
        $response = $this->getJson('api/katalog/Sambal-Kemangi');
        // Assert the response
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'title' => 'Sambal Kemangi',
            'description' => 'Test Description',
            'price' => "100000",
        ]);
    }
}
