<?php

namespace Tests\Feature;
// namespace Tests\Unit;

use App\Models\User;
use App\Models\Rekap;
use App\Models\Hutang;

use App\Models\CicilanHutang;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Http\Controllers\TransaksiController;
use App\Models\MethodePembayaran;
use App\Models\Product;
use App\Models\TemporaryImage;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
class HutangTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        
        // $this->withoutExceptionHandling();
        $this->artisan('db:seed');

        $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
    }

    private function insertHutangData(){
        $hutang = Hutang::create([
            'nama' => 'Bank asd',
            'catatan' => 'Catatan hutang',
            'status' => 0,
            'jumlah_hutang' => 500000,
            'tenggat_waktu' => '2024-10-30',
            'nominal' => 50000,
        ]);
    }
    public function test_list_hutang()
    {
        $this->insertHutangData();
        $hutang = Hutang::first();
        $response = $this->get(route('hutang.index'));
        $response->assertStatus(200);
        $response->assertViewIs('pages.hutang.index');
        $response->assertSeeText('Bank asd');

        $response = $this->get(route('hutang.create',[
            'status' => '0'
        ]));
        $response->assertStatus(200);
        $response->assertViewIs('pages.hutang.create');

        $response = $this->get(route('hutang.detail', $hutang->id));
        $response->assertStatus(200);
        $response->assertViewIs('pages.hutang.detail');

        $response = $this->get(route('hutang.edit', $hutang->id));
        $response->assertStatus(200);
        $response->assertViewIs('pages.hutang.edit');
    }

    
}