<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\TemporaryImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Storage;

class TemporaryImageTest extends TestCase
{
    /**
     * A basic unit test example.
     * ! sistem create product using filepond
     * ! penjelasan sistem fileupload, ketika image teruplaod id dropzone maka akan di store kedalam temporaryImage terlebih dahulu,
     * ! image yang sudah ada di temporary image dapat di hapus/delete maka itu adalh delete temporaryImage 
     * ! ketika di submit maka image akan dipindahkan dari temporaryImage kedalam folder image product utama
     * 
     * ! sistem edit product using filepond
     * ! ketika di halaman edit maka akan menampilkan detail prodcut, dan nanti di dropzone sudah ada image yang tertera, 
     * ! ketika image didelete maka image akan terhapus langugn dari folder image product utama, 
     * ! ketika mengedit product di form edit, image yang ingin di hapus, dihapusdari filepond, dan ketika di submit edit maka image product akan hilang langsung dari folder prodcut utama
     * ! ketika mengupload image product langsung dari filepond maka akan di store langsung kedaam folder image product utama
     */
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // $this->withoutExceptionHandling();
        $this->artisan('db:seed');
    }
    public function test_success_imageUpload_using_filepond_store_in_temporary_image()
    {
        $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $file = UploadedFile::fake()->image('test_image.jpg');

        $response = $this->postJson(route('upload.temporary'), [
            'images' => [$file],
        ]);
        // dd($response);
        $response->assertStatus(200);
        $folders = $response->json();

        // Ensure the file is stored
        Storage::disk('public')->assertExists('images/tmp/' . $folders[0] . '/' . $file->getClientOriginalName());

        // Ensure the database has the record
        $this->assertDatabaseHas('temporary_images', [
            'folder' => $folders[0],
            'file' => $file->getClientOriginalName(),
        ]);
    }
    public function test_delete_tempporary_image_using_filepond_that_image_has_stored()
    {
        $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        $file = UploadedFile::fake()->image('test_image.jpg');
        $folder = uniqid('image-', true);

        // Store image manually to simulate pre-existing temporary image
        $file->storeAs('public/images/tmp/' . $folder, $file->getClientOriginalName());
        TemporaryImage::create([
            'folder' => $folder,
            'file' => $file->getClientOriginalName(),
        ]);

        // Delete the temporary image
        $response = $this->postJson(route('delete.temporary'), [$folder]);

        $response->assertStatus(204);

        // Ensure the file is deleted
        Storage::disk('public')->assertMissing('images/tmp/' . $folder);

        // Ensure the database record is deleted
        $this->assertDatabaseMissing('temporary_images', [
            'folder' => $folder,
        ]);
    }
    public function test_create_product_moves_temp_images_to_product_folder()
    {
        $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        // Simulate file upload
        $file = UploadedFile::fake()->image('test_image.jpg');

        // Upload temporary image
        $response = $this->postJson(route('upload.temporary'), [
            'images' => [$file],
        ]);

        $response->assertStatus(200);
        $temporaryFolder = $response->json()[0];
        $fileName = $file->getClientOriginalName();

        // Assert temporary image exists
        Storage::disk('public')->assertExists("images/tmp/{$temporaryFolder}/{$fileName}");

        // Create product
        $productData = [
            'nama_product' => 'Test Product',
            'slug' => 'test-product',
            'harga' => '100000',
            'deskripsi' => 'Test Description',
            'link_shopee' => 'https://shopee.com/test',
            'stok' => '10',
            'tersedia' => '1',
            'spesifikasi_product' => 'Test Specifications',
            'images' => [json_encode([$temporaryFolder])],
            'varian' => ['Red', 'Blue']
        ];

        $response = $this->postJson(route('products.store'), $productData);
        // $response->assertStatus(201);

        // Assert product exists in the database
        $this->assertDatabaseHas('products', [
            'nama_product' => 'Test Product',
            'slug' => 'test-product',
        ]);


    }


    public function test_delete_image_product_in_form_edit()
    {
        $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        // Simulate file upload
        $file = UploadedFile::fake()->image('test_image.jpg');

        $response = $this->postJson(route('upload.temporary'), [
            'images' => [$file],
        ]);

        // Assert upload was successful
        $response->assertStatus(200);
        $folders = $response->json();

        // Ensure the file is stored
        $temporaryFolder = $folders[0]; // Get the folder from response
        // dd($temporaryFolder);
        $fileName = $file->getClientOriginalName();
        // dd($fileName);
        Storage::disk('public')->assertExists("images/tmp/{$temporaryFolder}/{$fileName}");

        // Create product
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
        $response = $this->postJson(route('products.store'), $productData);
        // $response->assertStatus(201);

        // Assert product exists in the database
        $this->assertDatabaseHas('products', [
            'nama_product' => 'Test Product',
            'slug' => 'test-product',
        ]);
        // Delete the temporary image
        $response = $this->postJson(route('delete.temporary'), [
            'folder' => $temporaryFolder,
        ]);

        $this->assertDatabaseMissing('temporary_images', [
            'folder' => $temporaryFolder,
        ]);
    }

    public function test_upload_image_direct_to_db_product_in_form_edit()
    {
        $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        // Simulate file upload
        $file = UploadedFile::fake()->image('test_image.jpg');

        $response = $this->postJson(route('upload.temporary'), [
            'images' => [$file],
        ]);

        // Assert upload was successful
        $response->assertStatus(200);
        $folders = $response->json();

        // Ensure the file is stored
        $temporaryFolder = $folders[0]; // Get the folder from response
        // dd($temporaryFolder);
        $fileName = $file->getClientOriginalName();
        // dd($fileName);
        Storage::disk('public')->assertExists("images/tmp/{$temporaryFolder}/{$fileName}");

        // Create product
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
        $response = $this->postJson(route('products.store'), $productData);
        $product = Product::where('nama_product', 'Test Product')->first();
        // $response->assertStatus(201);

        // Assert product exists in the database
        $this->assertDatabaseHas('products', [
            'nama_product' => 'Test Product',
            'slug' => 'test-product',
        ]);
        // Simulate file upload for the second image
        $file2 = UploadedFile::fake()->image('test_image.jpg');

        // Upload temporary image
        $response2 = $this->postJson(route('upload.directtoDB',$product->id), [
            'images' => [$file2],
        ]);
        $response2->assertStatus(200);
    }

}
