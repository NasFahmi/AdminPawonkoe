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

        $response = $this->get(route('cicilan.create', $hutang->id));
        $response->assertStatus(200);
        $response->assertViewIs('pages.cicilan.create');
    }

    public function test_list_and_create_hutang(){
        $response = $this->get(route('hutang.index'));
        $response->assertStatus(200);
        $response->assertViewIs('pages.hutang.index');

        $response = $this->get(route('hutang.create',[
            'status' => '0'
        ]));
        $response->assertStatus(200);
        $response->assertViewIs('pages.hutang.create');;

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

        $response->assertStatus(302);
        $response->assertRedirect(route('hutang.index'));
        $this->assertDatabaseHas('hutangs', [
            'nama' => $data['nama'],
            'catatan' => $data['catatan'],
            'status' => $data['status'],
            'jumlah_hutang' => $data['jumlahHutang'],
            'tenggat_waktu' => $data['tenggat_waktu'],
            'tanggal_lunas' => null,
        ]);
    }

    public function test_list_create_and_edit_hutang(){
        $response = $this->get(route('hutang.index'));
        $response->assertStatus(200);
        $response->assertViewIs('pages.hutang.index');

        $response = $this->get(route('hutang.create',[
            'status' => '0'
        ]));
        $response->assertStatus(200);
        $response->assertViewIs('pages.hutang.create');;

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

        $response->assertStatus(302);
        $response->assertRedirect(route('hutang.index'));
        $this->assertDatabaseHas('hutangs', [
            'nama' => $data['nama'],
            'catatan' => $data['catatan'],
            'status' => $data['status'],
            'jumlah_hutang' => $data['jumlahHutang'],
            'tenggat_waktu' => $data['tenggat_waktu'],
            'tanggal_lunas' => null,
        ]);

        $hutang = Hutang::where('nama', $data['nama'])->first();
        // dd($hutang);
        $response = $this->get(route('hutang.edit', $hutang->id));
        $response->assertStatus(200);
        $response->assertViewIs('pages.hutang.edit');

        $dataUpdate = [
            'nama' => 'Bank BRI update',
            'catatan' => 'Hutang buat bahan update',
            'jumlahHutang' => 1000000,
            'tanggal_lunas' => null,
            'tenggat_waktu' => '2024-10-25',
            'nominal' => 200000,
            'status' => 0,
        ];
        $response = $this->patch(route('hutang.update', $hutang->id), $dataUpdate);

        $response->assertStatus(302);
        $response->assertRedirect(route('hutang.index'));
        $this->assertDatabaseHas('hutangs', [
            'nama' => $dataUpdate['nama'],
            'catatan' => $dataUpdate['catatan'],
            'status' => $dataUpdate['status'],
            'jumlah_hutang' => $dataUpdate['jumlahHutang'],
            'tenggat_waktu' => $dataUpdate['tenggat_waktu'],
            'tanggal_lunas' => null,
        ]);
    }


    public function test_list_create_edit_and_cicil_hutang(){
        $response = $this->get(route('hutang.index'));
        $response->assertStatus(200);
        $response->assertViewIs('pages.hutang.index');

        $response = $this->get(route('hutang.create',[
            'status' => '0'
        ]));
        $response->assertStatus(200);
        $response->assertViewIs('pages.hutang.create');;

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

        $response->assertStatus(302);
        $response->assertRedirect(route('hutang.index'));
        $this->assertDatabaseHas('hutangs', [
            'nama' => $data['nama'],
            'catatan' => $data['catatan'],
            'status' => $data['status'],
            'jumlah_hutang' => $data['jumlahHutang'],
            'tenggat_waktu' => $data['tenggat_waktu'],
            'tanggal_lunas' => null,
        ]);

        $hutang = Hutang::where('nama', $data['nama'])->first();
        // dd($hutang);
        $response = $this->get(route('hutang.edit', $hutang->id));
        $response->assertStatus(200);
        $response->assertViewIs('pages.hutang.edit');

        $dataUpdate = [
            'nama' => 'Bank BRI update',
            'catatan' => 'Hutang buat bahan update',
            'jumlahHutang' => 1000000,
            'tanggal_lunas' => null,
            'tenggat_waktu' => '2024-10-25',
            'nominal' => 200000,
            'status' => 0,
        ];
        $response = $this->patch(route('hutang.update', $hutang->id), $dataUpdate);

        $response->assertStatus(302);
        $response->assertRedirect(route('hutang.index'));
        $this->assertDatabaseHas('hutangs', [
            'nama' => $dataUpdate['nama'],
            'catatan' => $dataUpdate['catatan'],
            'status' => $dataUpdate['status'],
            'jumlah_hutang' => $dataUpdate['jumlahHutang'],
            'tenggat_waktu' => $dataUpdate['tenggat_waktu'],
            'tanggal_lunas' => null,
        ]);

        $response = $this->get(route('cicilan.create', $hutang->id));
        $response->assertStatus(200);
        $response->assertViewIs('pages.cicilan.create');

        $dataCicil = [
            'nominal' => 100000,
        ];
        $response = $this->post(route('cicilan.store', $hutang->id), $dataCicil);

        $this->assertDatabaseHas('cicilan_hutangs', [
            'nominal' => $dataCicil['nominal'],
        ]);
    }

    public function test_list_create_and_delete_hutang(){
        $response = $this->get(route('hutang.index'));
        $response->assertStatus(200);
        $response->assertViewIs('pages.hutang.index');

        $response = $this->get(route('hutang.create',[
            'status' => '0'
        ]));
        $response->assertStatus(200);
        $response->assertViewIs('pages.hutang.create');;

        $data = [
            'nama' => 'Bank BRI',
            'catatan' => 'Hutang buat bahan',
            'jumlahHutang' => 1000000,
            'tanggal_lunas' => null,
            'tenggat_waktu' => '2024-10-25 00:00:00',
            'nominal' => 100000,
            'status' => 0,
        ];
        $response = $this->post(route('hutang.store'), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('hutang.index'));
        $this->assertDatabaseHas('hutangs', [
            'nama' => $data['nama'],
            'catatan' => $data['catatan'],
            'status' => $data['status'],
            'jumlah_hutang' => $data['jumlahHutang'],
            'tenggat_waktu' => $data['tenggat_waktu'],
            'tanggal_lunas' => null,
        ]);
        $hutang = Hutang::where('nama', $data['nama'])->first();
        $response = $this->delete(route('hutang.destroy', $hutang->id));
        $response->assertStatus(302);
        $response->assertRedirect(route('hutang.index'));

        $this->assertSoftDeleted('hutangs', [
            'id' => $hutang->id,
        ]);
    }

    public function test_list_create_cicil_delete_hutang(){
        $response = $this->get(route('hutang.index'));
        $response->assertStatus(200);
        $response->assertViewIs('pages.hutang.index');

        $response = $this->get(route('hutang.create',[
            'status' => '0'
        ]));
        $response->assertStatus(200);
        $response->assertViewIs('pages.hutang.create');;

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

        $response->assertStatus(302);
        $response->assertRedirect(route('hutang.index'));
        $this->assertDatabaseHas('hutangs', [
            'nama' => $data['nama'],
            'catatan' => $data['catatan'],
            'status' => $data['status'],
            'jumlah_hutang' => $data['jumlahHutang'],
            'tenggat_waktu' => $data['tenggat_waktu'],
            'tanggal_lunas' => null,
        ]);

        $hutang = Hutang::where('nama', $data['nama'])->first();

        $response = $this->get(route('cicilan.create', $hutang->id));
        $response->assertStatus(200);
        $response->assertViewIs('pages.cicilan.create');

        $dataCicil = [
            'nominal' => 100000,
        ];
        $response = $this->post(route('cicilan.store', $hutang->id), $dataCicil);

        $this->assertDatabaseHas('cicilan_hutangs', [
            'nominal' => $dataCicil['nominal'],
        ]);

        $response = $this->delete(route('hutang.destroy', $hutang->id));
        $response->assertStatus(302);
        $response->assertRedirect(route('hutang.index'));

        $this->assertSoftDeleted('hutangs', [
            'id' => $hutang->id,
        ]);
    }

    public function test_list_create_cicil_edit_delete_hutang_done(){
        $response = $this->get(route('hutang.index'));
        $response->assertStatus(200);
        $response->assertViewIs('pages.hutang.index');

        $response = $this->get(route('hutang.create',[
            'status' => '0'
        ]));
        $response->assertStatus(200);
        $response->assertViewIs('pages.hutang.create');;

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

        $response->assertStatus(302);
        $response->assertRedirect(route('hutang.index'));
        $this->assertDatabaseHas('hutangs', [
            'nama' => $data['nama'],
            'catatan' => $data['catatan'],
            'status' => $data['status'],
            'jumlah_hutang' => $data['jumlahHutang'],
            'tenggat_waktu' => $data['tenggat_waktu'],
            'tanggal_lunas' => null,
        ]);

        $hutang = Hutang::where('nama', $data['nama'])->first();
        // dd($hutang);
        $response = $this->get(route('hutang.edit', $hutang->id));
        $response->assertStatus(200);
        $response->assertViewIs('pages.hutang.edit');

        $dataUpdate = [
            'nama' => 'Bank BRI update',
            'catatan' => 'Hutang buat bahan update',
            'jumlahHutang' => 1000000,
            'tanggal_lunas' => null,
            'tenggat_waktu' => '2024-10-25',
            'nominal' => 200000,
            'status' => 0,
        ];
        $response = $this->patch(route('hutang.update', $hutang->id), $dataUpdate);

        $response->assertStatus(302);
        $response->assertRedirect(route('hutang.index'));
        $this->assertDatabaseHas('hutangs', [
            'nama' => $dataUpdate['nama'],
            'catatan' => $dataUpdate['catatan'],
            'status' => $dataUpdate['status'],
            'jumlah_hutang' => $dataUpdate['jumlahHutang'],
            'tenggat_waktu' => $dataUpdate['tenggat_waktu'],
            'tanggal_lunas' => null,
        ]);

        $response = $this->get(route('cicilan.create', $hutang->id));
        $response->assertStatus(200);
        $response->assertViewIs('pages.cicilan.create');

        $dataCicil = [
            'nominal' => 100000,
        ];
        $response = $this->post(route('cicilan.store', $hutang->id), $dataCicil);

        $this->assertDatabaseHas('cicilan_hutangs', [
            'nominal' => $dataCicil['nominal'],
        ]);

        $response = $this->delete(route('hutang.destroy', $hutang->id));
        $response->assertStatus(302);
        $response->assertRedirect(route('hutang.index'));

        $this->assertSoftDeleted('hutangs', [
            'id' => $hutang->id,
        ]);
    }
}