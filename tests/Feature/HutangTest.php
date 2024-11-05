<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use App\Models\Hutang;
use App\Models\CicilanHutang;
use App\Models\Rekap;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Artisan;

class HutangTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use WithoutMiddleware, RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        
        // $this->withoutExceptionHandling();

        Artisan::call('migrate --seed');
    }

    // HTG-BS-001
    public function test_make_hutang_undone_success(): void
    {
        // $user = User::findOrFail(id: 2); // Pastikan user ini ada
        // $this->actingAs($user);

        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.dashboard'));

        $data = [
            'nama' => 'Bank BRI',
            'catatan' => 'Hutang buat bahan',
            'jumlahHutang' => 1000000,
            'tanggal_lunas' => null,
            'tenggat_waktu' => '2024-10-25',
            'nominal' => 100000,
            'status' => 0,
        ];

        // Send POST request
        $response = $this->withoutMiddleware()
        ->post(route('hutang.store'), $data);
        // dump($response->status(), $response->getContent());

        // Assert status code
        $response->assertStatus(302); 
        $response->assertRedirect(route('hutang.index'));

        // Check if hutang was saved to the database
        $this->assertDatabaseHas('hutangs', [
            'nama' => $data['nama'],
            'catatan' => $data['catatan'],
            'status' => $data['status'],
            'jumlah_hutang' => $data['jumlahHutang'],
            'tenggat_waktu' => $data['tenggat_waktu'],
            'tanggal_lunas' => null,
        ]);
    }

    // HTG-BS-002
    public function test_make_hutang_undone_fail_field_nama(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.dashboard'));

        $data = [
            'nama' => 'Bank BRI',
            'catatan' => '',
            'jumlahHutang' => '',
            'tanggal_lunas' => null,
            'tenggat_waktu' => '',
            'nominal' => '',
            'status' => 0,
        ];

        // Send POST request
        $response = $this->withoutMiddleware()
        ->post(route('hutang.store'), $data);
        // dd($response->status(), $response->getContent());

        // Assert status code
        // $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'jumlahHutang' => 'The jumlah hutang field is required.',
            'nominal' => 'The nominal field is required.',
            'tenggat_waktu' => 'The tenggat waktu field is required.'
        ]); 
    }

    // HTG-BS-003
    public function test_make_hutang_undone_fail_field_nominal(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.dashboard'));

        $data = [
            'nama' => '',
            'catatan' => '',
            'jumlahHutang' => '',
            'tanggal_lunas' => null,
            'tenggat_waktu' => '',
            'nominal' => '100000',
            'status' => 0,
        ];

        // Send POST request
        $response = $this->post(route('hutang.store'), $data);
        // dd($response->status(), $response->getContent());

        // Assert status code
        // $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'nama' => 'The nama field is required.',
            'jumlahHutang' => 'The jumlah hutang field is required.',
            'tenggat_waktu' => 'The tenggat waktu field is required.'
        ]); 
    }

    // HTG-BS-004
    public function test_make_hutang_undone_fail_field_jumlahHutang(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.dashboard'));

        $data = [
            'nama' => '',
            'catatan' => '',
            'jumlahHutang' => '1000000',
            'tanggal_lunas' => null,
            'tenggat_waktu' => '',
            'nominal' => '',
            'status' => 0,
        ];

        // Send POST request
        $response = $this->post(route('hutang.store'), $data);
        // dd($response->status(), $response->getContent());

        $response->assertSessionHasErrors([
            'nama' => 'The nama field is required.',
            'nominal' => 'The nominal field is required.',
            'tenggat_waktu' => 'The tenggat waktu field is required.'
        ]); 
    }

    // HTG-BS-005
    public function test_make_hutang_undone_fail_field_catatan(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.dashboard'));

        $data = [
            'nama' => '',
            'catatan' => 'hutang modal tambahan',
            'jumlahHutang' => '',
            'tanggal_lunas' => null,
            'tenggat_waktu' => '',
            'nominal' => '',
            'status' => 0,
        ];

        // Send POST request
        $response = $this->post(route('hutang.store'), $data);
        // dd($response->status(), $response->getContent());

        // Assert status code
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'nama' => 'The nama field is required.',
            'nominal' => 'The nominal field is required.',
            'jumlahHutang' => 'The jumlah hutang field is required.',
            'tenggat_waktu' => 'The tenggat waktu field is required.'
        ]); 
    }

    //HTG-BS-006
    public function test_make_hutang_undone_fail_field_tenggat_waktu(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.dashboard'));

        $data = [
            'nama' => '',
            'catatan' => '',
            'jumlahHutang' => '',
            'tanggal_lunas' => null,
            'tenggat_waktu' => '2024-10-30',
            'nominal' => '',
            'status' => 0,
        ];

        // Send POST request
        $response = $this->post(route('hutang.store'), $data);
        // dd($response->status(), $response->getContent());

        // Assert status code
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'nama' => 'The nama field is required.',
            'nominal' => 'The nominal field is required.',
            'jumlahHutang' => 'The jumlah hutang field is required.'
        ]); 
    }

    //HTG-BS-007
    public function test_make_hutang_undone_fail_field_nama_and_catatan(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.dashboard'));

        $data = [
            'nama' => 'Bank BRI',
            'catatan' => 'hutang untuk modal tambahan',
            'jumlahHutang' => '',
            'tanggal_lunas' => null,
            'tenggat_waktu' => '',
            'nominal' => '',
            'status' => 0,
        ];

        // Send POST request
        $response = $this->post(route('hutang.store'), $data);
        // dd($response->status(), $response->getContent());

        // Assert status code
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'nominal' => 'The nominal field is required.',
            'jumlahHutang' => 'The jumlah hutang field is required.',
            'tenggat_waktu' => 'The tenggat waktu field is required.'
        ]); 
    }

    //HTG-BS-008
    public function test_make_hutang_undone_fail_field_nama_and_nominal(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.dashboard'));

        $data = [
            'nama' => 'Bank BRI',
            'catatan' => '',
            'jumlahHutang' => '',
            'tanggal_lunas' => null,
            'tenggat_waktu' => '',
            'nominal' => 100000,
            'status' => 0,
        ];

        // Send POST request
        $response = $this->post(route('hutang.store'), $data);
        // dd($response->status(), $response->getContent());

        // Assert status code
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'jumlahHutang' => 'The jumlah hutang field is required.',
            'tenggat_waktu' => 'The tenggat waktu field is required.'
        ]); 
    }

    //HTG-BS-009
    public function test_make_hutang_undone_fail_field_nama_and_jumlahHutang(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.dashboard'));

        $data = [
            'nama' => 'Bank BRI',
            'catatan' => '',
            'jumlahHutang' => 1000000,
            'tanggal_lunas' => null,
            'tenggat_waktu' => '',
            'nominal' => '',
            'status' => 0,
        ];

        // Send POST request
        $response = $this->post(route('hutang.store'), $data);
        // dd($response->status(), $response->getContent());

        // Assert status code
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'nominal' => 'The nominal field is required.',
            'tenggat_waktu' => 'The tenggat waktu field is required.'
        ]); 
    }

    //HTG-BS-010
    public function test_make_hutang_undone_fail_field_nama_and_tenggat_waktu(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.dashboard'));

        $data = [
            'nama' => 'Bank BRI',
            'catatan' => '',
            'jumlahHutang' => '',
            'tanggal_lunas' => null,
            'tenggat_waktu' => '2024-10-30',
            'nominal' => '',
            'status' => 0,
        ];

        // Send POST request
        $response = $this->post(route('hutang.store'), $data);
        // dd($response->status(), $response->getContent());

        // Assert status code
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'nominal' => 'The nominal field is required.',
            'jumlahHutang' => 'The jumlah hutang field is required.',
        ]); 
    }

    //HTG-BS-011
    public function test_make_hutang_undone_fail_field_nama_catatan_and_nominal(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.dashboard'));

        $data = [
            'nama' => 'Bank BRI',
            'catatan' => 'hutang buat bahan',
            'jumlahHutang' => '',
            'tanggal_lunas' => null,
            'tenggat_waktu' => '',
            'nominal' => 100000,
            'status' => 0,
        ];

        // Send POST request
        $response = $this->post(route('hutang.store'), $data);
        // dd($response->status(), $response->getContent());

        // Assert status code
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'jumlahHutang' => 'The jumlah hutang field is required.',
            'tenggat_waktu' => 'The tenggat waktu field is required.'
        ]); 
    }

    //HTG-BS-012
    public function test_make_hutang_undone_fail_field_nama_catatan_and_jumlahHutang(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.dashboard'));

        $data = [
            'nama' => 'Bank BRI',
            'catatan' => 'hutang buat bahan',
            'jumlahHutang' => 1000000,
            'tanggal_lunas' => null,
            'tenggat_waktu' => '',
            'nominal' => '',
            'status' => 0,
        ];

        // Send POST request
        $response = $this->post(route('hutang.store'), $data);
        // dd($response->status(), $response->getContent());

        // Assert status code
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'nominal' => 'The nominal field is required.',
            'tenggat_waktu' => 'The tenggat waktu field is required.'
        ]); 
    }

    //HTG-BS-013
    public function test_make_hutang_undone_fail_field_nama_catatan_and_tenggat_waktu(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.dashboard'));

        $data = [
            'nama' => 'Bank BRI',
            'catatan' => 'hutang buat bahan',
            'jumlahHutang' => '',
            'tanggal_lunas' => null,
            'tenggat_waktu' => '2024-10-30',
            'nominal' => '',
            'status' => 0,
        ];

        // Send POST request
        $response = $this->post(route('hutang.store'), $data);
        // dd($response->status(), $response->getContent());

        // Assert status code
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'nominal' => 'The nominal field is required.',
            'jumlahHutang' => 'The jumlah hutang field is required.',
        ]); 
    }

    //HTG-BS-014
    public function test_make_hutang_undone_fail_field_nama_unfilled(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.dashboard'));

        $data = [
            'nama' => '',
            'catatan' => 'hutang buat bahan',
            'jumlahHutang' => 1000000,
            'tanggal_lunas' => null,
            'tenggat_waktu' => '2024-10-30',
            'nominal' => 100000,
            'status' => 0,
        ];

        // Send POST request
        $response = $this->post(route('hutang.store'), $data);
        // dd($response->status(), $response->getContent());

        // Assert status code
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'nama' => 'The nama field is required.',
        ]); 
    }

    //HTG-BS-015
    public function test_make_hutang_undone_fail_field_nominal_unfilled(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.dashboard'));

        $data = [
            'nama' => 'Bank BRI',
            'catatan' => 'hutang buat bahan',
            'jumlahHutang' => 1000000,
            'tanggal_lunas' => null,
            'tenggat_waktu' => '2024-10-30',
            'nominal' => '',
            'status' => 0,
        ];

        // Send POST request
        $response = $this->post(route('hutang.store'), $data);
        // dd($response->status(), $response->getContent());

        // Assert status code
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'nominal' => 'The nominal field is required.',
        ]); 
    }

    //HTG-BS-016
    public function test_make_hutang_undone_fail_field_jumlahHutang_unfilled(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.dashboard'));

        $data = [
            'nama' => 'Bank BRI',
            'catatan' => 'hutang buat bahan',
            'jumlahHutang' => '',
            'tanggal_lunas' => null,
            'tenggat_waktu' => '2024-10-30',
            'nominal' => 100000,
            'status' => 0,
        ];

        // Send POST request
        $response = $this->post(route('hutang.store'), $data);
        // dd($response->status(), $response->getContent());

        // Assert status code
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'jumlahHutang' => 'The jumlah hutang field is required.'
        ]); 
    }

    //HTG-BS-017
    public function test_make_hutang_undone_fail_field_tenggat_waktu_unfilled(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.dashboard'));

        $data = [
            'nama' => 'Bank BRI',
            'catatan' => 'hutang buat bahan',
            'jumlahHutang' => 1000000,
            'tanggal_lunas' => null,
            'tenggat_waktu' => '',
            'nominal' => 100000,
            'status' => 0,
        ];

        // Send POST request
        $response = $this->post(route('hutang.store'), $data);
        // dd($response->status(), $response->getContent());

        // Assert status code
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'tenggat_waktu' => 'The tenggat waktu field is required.'
        ]); 
    }

    //HTG-BS-018
    public function test_make_hutang_undone_fail_all_field_unfilled(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.dashboard'));

        $data = [
            'nama' => '',
            'catatan' => '',
            'jumlahHutang' => '',
            'tanggal_lunas' => null,
            'tenggat_waktu' => '',
            'nominal' => '',
            'status' => 0,
        ];

        // Send POST request
        $response = $this->post(route('hutang.store'), $data);
        // dd($response->status(), $response->getContent());

        // Assert status code
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'nama' => 'The nama field is required.',
            'nominal' => 'The nominal field is required.',
            'jumlahHutang' => 'The jumlah hutang field is required.',
            'tenggat_waktu' => 'The tenggat waktu field is required.'
        ]); 
    }

    //HTG-BS-019
    public function test_make_hutang_undone_fail_field_nama_invalid(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.dashboard'));

        $data = [
            'nama' => '$$!@#!@asd',
            'catatan' => 'hutang buat bahan',
            'jumlahHutang' => 1000000,
            'tanggal_lunas' => null,
            'tenggat_waktu' => 2024-10-30,
            'nominal' => 100000,
            'status' => 0,
        ];

        // Send POST request
        $response = $this->post(route('hutang.store'), $data);
        // dd($response->status(), $response->getContent());

        // Assert status code
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'nama' => 'Nama hanya boleh mengandung huruf dan spasi.'
        ]); 
    }

    //HTG-BS-020
    public function test_make_hutang_undone_fail_field_nominal_invalid(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.dashboard'));

        $data = [
            'nama' => 'Bank BRI',
            'catatan' => 'hutang buat bahan',
            'jumlahHutang' => 1000000,
            'tanggal_lunas' => null,
            'tenggat_waktu' => 2024-10-30,
            'nominal' => -1,
            'status' => 0,
        ];

        // Send POST request
        $response = $this->post(route('hutang.store'), $data);
        // dd($response->status(), $response->getContent());

        // Assert status code
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'nominal' => 'Nominal cicilan awal harus berupa angka.'
        ]); 
    }

    //HTG-BS-021
    public function test_make_hutang_undone_fail_field_jumlahHutang_invalid(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.dashboard'));

        $data = [
            'nama' => 'Bank BRI',
            'catatan' => 'hutang buat bahan',
            'jumlahHutang' => -1,
            'tanggal_lunas' => null,
            'tenggat_waktu' => 2024-10-30,
            'nominal' => 100000,
            'status' => 0,
        ];

        // Send POST request
        $response = $this->post(route('hutang.store'), $data);
        // dd($response->status(), $response->getContent());

        // Assert status code
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'jumlahHutang' => 'Jumlah hutang harus berupa angka.'
        ]); 
    }

    //HTG-BS-022
    public function test_make_hutang_undone_fail_field_tenggat_waktu_invalid(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.dashboard'));

        $data = [
            'nama' => 'Bank BRI',
            'catatan' => 'hutang buat bahan',
            'jumlahHutang' => 1000000,
            'tanggal_lunas' => null,
            'tenggat_waktu' => 2002-20-30,
            'nominal' => 100000,
            'status' => 0,
        ];

        // Send POST request
        $response = $this->post(route('hutang.store'), $data);
        // dd($response->status(), $response->getContent());

        // Assert status code
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'tenggat_waktu' => 'Tenggat waktu harus berupa tanggal dengan format YYYY-MM-DD.'
        ]); 
    }

    //ERORR
    //HTG-BS-023
    public function test_edit_hutang_undone_success(): void 
{        
    $this->withoutExceptionHandling();
    
    // Buat data hutang
    $hutang = Hutang::create([
        'nama' => 'Bank ABC',
        'catatan' => 'Catatan hutang',
        'status' => 0,
        'jumlah_hutang' => 500000,
        'tenggat_waktu' => '2024-10-30',
        'tanggal_lunas' => null,
    ]);
    $id = $hutang->id;
    // Create initial cicilan
    CicilanHutang::create([
        'nominal' => 50000,
        'hutangId' => $id, // Associate it with the hutang
    ]);
    // dd($hutang->id);
    // Data yang akan diupdate
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

    // Kirim request PATCH
    $response = $this->patch(route('hutang.update', $id), $updatedData);
    
    // Assertions
    $response->assertStatus(302);
    $response->assertRedirect(route('hutang.index'));

    // Cek database
    $updatedHutang = Hutang::find($id);
    $this->assertEquals('Bank XYZ', $updatedHutang->nama);
    $this->assertEquals(600000, $updatedHutang->jumlah_hutang);
    
    // Verify cicilan has been updated or created correctly
    $updatedCicilan = CicilanHutang::where('hutangId', $id)->first();
    $this->assertNotNull($updatedCicilan);
    $this->assertEquals(60000, $updatedCicilan->nominal); // Assuming you want to verify the cicilan nominal
}

    //HTG-BS-024
    public function test_edit_hutang_undone_to_done_success(): void
    {
        $this->withoutExceptionHandling();

        $hutang = Hutang::create([
            'nama' => 'Bank ABC',
            'catatan' => 'Catatan hutang',
            'status' => 0,
            'jumlah_hutang' => 500000,
            'tenggat_waktu' => '2024-10-30',
            'tanggal_lunas' => null,
        ]);
        CicilanHutang::create([
            'nominal' => 50000,
            'hutangId' => $hutang->id,
        ]);

        $updatedData = [
            'nama' => 'Bank XYZ', // Nama baru
            'catatan' => 'Catatan hutang diperbarui',
            'status' => 1, // Status 1 (done)
            'jumlahHutang' => 600000, 
            'tenggat_waktu' => null, // Tanggal tenggat baru
            'tanggal_lunas' => '2024-12-31',
            'nominal' => 60000,
        ];

        // Kirim request PUT ke rute update
        $response = $this->patch(route('hutang.update', $hutang->id), $updatedData);

        // Pastikan response sukses (biasanya 302 untuk redirect setelah edit)
        $response->assertStatus(302);
        $response->assertRedirect(route('hutang.index'));
        // dd(Hutang::find($hutang->id));
        $hutang->refresh();
        $updatedHutang = Hutang::find($hutang->id);
        // dd($updatedHutang);
        // $this->assertEquals('Bank XYZ', $updatedHutang->nama);
        // $this->assertEquals(600000, $updatedHutang->jumlah_hutang);
        $this->assertEquals(1, $updatedHutang->status);
        // Pastikan tidak ada cicilan terkait hutang ini di database
        $this->assertDatabaseMissing('cicilan_hutangs', [
            'hutangId' => $hutang->id,
        ]);
    }
    //HTG-BS-025
    public function test_delete_hutang_undone_success(): void
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

        // Pastikan data berhasil dibuat
        $this->assertDatabaseHas('hutangs', [
            'id' => $hutang->id,
            'nama' => 'Bank ABC',
        ]);

        // Kirim request untuk menghapus data
        $response = $this->delete(route('hutang.destroy', $hutang->id));
        // dd($response->getContent());
        $response->assertStatus(302);
        $response->assertRedirect(route('hutang.index'));

        // Pastikan data tidak ada lagi di database
        $this->assertSoftDeleted('hutangs', [
            'id' => $hutang->id,
        ]);

        $deletedHutang = Hutang::withTrashed()->find($hutang->id);
        $this->assertNotNull($deletedHutang->deleted_at, 'Kolom deleted_at harus terisi setelah soft delete.');
    }

    //HTG-BS-026
    public function test_see_detail_hutang_undone_success(): void
    {
        // Autentikasi pengguna
        $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
    
        // Buat data hutang
        $hutang = Hutang::create([
            'nama' => 'Bank asd',
            'catatan' => 'Catatan hutang',
            'status' => 0,
            'jumlah_hutang' => 500000,
            'tenggat_waktu' => '2024-10-30',
            'nominal' => 50000,
        ]);
    
        // Panggil metode show untuk melihat detail hutang
        $response = $this->get(route('hutang.detail', $hutang->id));
    
        // Pastikan respons sukses (biasanya 200 untuk tampilan detail)
        $response->assertStatus(200);
        // dd($response->viewData('hutangData'));

        $response->assertViewIs('pages.hutang.detail');

        // Periksa setiap atribut secara langsung
        $response->assertViewHas('hutangData');
        $viewHutangData = $response->viewData('hutangData');

        // Pastikan setiap atribut cocok
        $this->assertEquals($hutang->id, $viewHutangData->id);
        $this->assertEquals($hutang->nama, $viewHutangData->nama);
        $this->assertEquals($hutang->catatan, $viewHutangData->catatan);
        $this->assertEquals($hutang->status, $viewHutangData->status);
        $this->assertEquals($hutang->jumlah_hutang, $viewHutangData->jumlah_hutang);
        $this->assertEquals($hutang->nominal, $viewHutangData->nominal);
        $this->assertTrue(
            Carbon::parse($hutang->tenggat_waktu)->eq(Carbon::parse($viewHutangData->tenggat_waktu))
        );    
    }
    
    //HTG-BS-026
    public function test_paying_hutang_undone_to_done_successfully(): void
{
    // Disable exception handling for better debugging during tests
    $this->withoutExceptionHandling();

    // Given a hutang with a certain amount
    $hutang = Hutang::create([
        'nama' => 'Bank ABC',
        'catatan' => 'Catatan hutang',
        'jumlah_hutang' => 100000, // Total amount of the debt
        'status' => 0, // Status indicating it's unpaid
        'tenggat_waktu' => '2024-11-30 23:59:59', // Example deadline
    ]);

    // Create an initial cicilan for the hutang
    CicilanHutang::create([
        'nominal' => 50000, // Existing cicilan amount
        'hutangId' => $hutang->id, // Associate it with the hutang
    ]);

    // When a valid cicilan payment is made
    $response = $this->post(route('cicilan.store', $hutang->id), [
        'nominal' => 50000, // New payment to add
    ]);

    // And a success message should be in the session
    $response->assertSessionHas('success', 'Data Berhasil Disimpan');

    // Assert that the hutang status was updated to paid
    $hutang->refresh(); // Refresh the hutang model instance from the database
    $this->assertEquals(1, $hutang->status); // Check if status is updated to '1' (paid)
    $this->assertNotNull($hutang->tanggal_lunas); // Check if the date of payment is set
    }

    
    //HTG-BS-027
    public function test_paying_hutang_undone_success(): void
    {
            // Disable exception handling for better debugging during tests
        $this->withoutExceptionHandling();

        // Given a hutang with a certain amount
        $hutang = Hutang::create([
            'nama' => 'Bank ABC',
            'catatan' => 'Catatan hutang',
            'jumlah_hutang' => 100000, // Total amount of the debt
            'status' => 0, // Status indicating it's unpaid
            'tenggat_waktu' => '2024-11-30 23:59:59', // Example deadline
        ]);

        // Create an initial cicilan for the hutang
        CicilanHutang::create([
            'nominal' => 50000, // Existing cicilan amount
            'hutangId' => $hutang->id, // Associate it with the hutang
        ]);

        // When a valid cicilan payment is made
        $response = $this->post(route('cicilan.store', $hutang->id), [
            'nominal' => 20000, // New payment to add
        ]);

        // And a success message should be in the session
        $response->assertSessionHas('success', 'Data Berhasil Disimpan');

        // Assert that the hutang status was updated to paid
        $hutang->refresh(); // Refresh the hutang model instance from the database
        $this->assertEquals(0, $hutang->status); // Check if status is updated to '1' (paid)
    }


    //HTG-S-028
    public function test_make_hutang_done_success(): void
    {
        // Authentication
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.dashboard'));

        // Prepare hutang data
        $data = [
            'nama' => 'Bank BRI',
            'catatan' => 'hutang buat bahan',
            'jumlahHutang' => 1000000,
            'tanggal_lunas' => '2024-10-30',
            'tenggat_waktu' => null,
            'nominal' => null,
            'status' => 1,
        ];

        // Send POST request with withoutMiddleware()
        $response = $this->post(route('hutang.store'), $data);

        // Optional debugging if needed
        // if ($response->status() !== 302) {
        //     dump($response->status(), $response->getContent());
        // }

        // Assert redirect
        $response->assertStatus(302);
        $response->assertRedirect(route('hutang.index'));

        // Check database
        $this->assertDatabaseHas('hutangs', [
            'nama' => $data['nama'],
            'catatan' => $data['catatan'],
            'status' => $data['status'],
            'jumlah_hutang' => $data['jumlahHutang'],
            'tenggat_waktu' => $data['tenggat_waktu'],
            'tanggal_lunas' => $data['tanggal_lunas'],
        ]);
    }

    // HTG-S-029
    public function test_make_hutang_done_fail_field_nama(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.dashboard'));

        $data = [
            'nama' => 'Bank BRI',
            'catatan' => '',
            'jumlahHutang' => '',
            'tanggal_lunas' => '',
            'tenggat_waktu' => null,
            'nominal' => null,
            'status' => 1,
        ];

        // Send POST request
        $response = $this->withoutMiddleware()
        ->post(route('hutang.store'), $data);
        // dd($response->status(), $response->getContent());

        // Assert status code
        // $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'jumlahHutang' => 'The jumlah hutang field is required.',
            'tanggal_lunas' => 'The tanggal lunas field is required.'
        ]); 
    }
   

    // HTG-S-031
    public function test_make_hutang_done_fail_field_jumlahHutang(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.dashboard'));

        $data = [
            'nama' => '',
            'catatan' => '',
            'jumlahHutang' => 1000000,
            'tanggal_lunas' => '',
            'tenggat_waktu' => null,
            'nominal' => null,
            'status' => 1,
        ];

        // Send POST request
        $response = $this->post(route('hutang.store'), $data);
        // dd($response->status(), $response->getContent());

        $response->assertSessionHasErrors([
            'nama' => 'The nama field is required.',
            'tanggal_lunas' => 'The tanggal lunas field is required.'
        ]); 
    }

    // HTG-S-032
    public function test_make_hutang_done_fail_field_catatan(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.dashboard'));

        $data = [
            'nama' => '',
            'catatan' => 'hutang modal tambahan',
            'jumlahHutang' => '',
            'tanggal_lunas' => '',
            'tenggat_waktu' => null,
            'nominal' => null,
            'status' => 1,
        ];

        // Send POST request
        $response = $this->post(route('hutang.store'), $data);
        // dd($response->status(), $response->getContent());

        // Assert status code
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'nama' => 'The nama field is required.',
            'jumlahHutang' => 'The jumlah hutang field is required.',
            'tanggal_lunas' => 'The tanggal lunas field is required.',
        ]); 
    }

    //HTG-S-033
    public function test_make_hutang_done_fail_field_tanggal_lunas(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.dashboard'));

        $data = [
            'nama' => '',
            'catatan' => '',
            'jumlahHutang' => '',
            'tanggal_lunas' => 2024-10-30,
            'tenggat_waktu' => null,
            'nominal' => null,
            'status' => 1,
        ];

        // Send POST request
        $response = $this->post(route('hutang.store'), $data);
        // dd($response->status(), $response->getContent());

        // Assert status code
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'nama' => 'The nama field is required.',
            'jumlahHutang' => 'The jumlah hutang field is required.'
        ]); 
    }

    //HTG-S-034
    public function test_make_hutang_done_fail_field_nama_and_catatan(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.dashboard'));

        $data = [
            'nama' => 'Bank BRI',
            'catatan' => 'hutang untuk modal tambahan',
            'jumlahHutang' => '',
            'tanggal_lunas' => '',
            'tenggat_waktu' => null,
            'nominal' => null,
            'status' => 1,
        ];

        // Send POST request
        $response = $this->post(route('hutang.store'), $data);
        // dd($response->status(), $response->getContent());

        // Assert status code
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'jumlahHutang' => 'The jumlah hutang field is required.',
            'tanggal_lunas' => 'The tanggal lunas field is required.'
        ]); 
    }

   
    //HTG-S-035
    public function test_make_hutang_done_fail_field_nama_and_jumlahHutang(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.dashboard'));

        $data = [
            'nama' => 'Bank BRI',
            'catatan' => '',
            'jumlahHutang' => 1000000,
            'tanggal_lunas' => '',
            'tenggat_waktu' => null,
            'nominal' => null,
            'status' => 1,
        ];

        // Send POST request
        $response = $this->post(route('hutang.store'), $data);
        // dd($response->status(), $response->getContent());

        // Assert status code
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'tanggal_lunas' => 'The tanggal lunas field is required.',
        ]); 
    }

    //HTG-S-036
    public function test_make_hutang_done_fail_field_nama_and_tanggal_lunas(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.dashboard'));

        $data = [
            'nama' => 'Bank BRI',
            'catatan' => '',
            'jumlahHutang' => '',
            'tanggal_lunas' => 2024-10-30,
            'tenggat_waktu' => null,
            'nominal' => null,
            'status' => 1,
        ];

        // Send POST request
        $response = $this->post(route('hutang.store'), $data);
        // dd($response->status(), $response->getContent());

        // Assert status code
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'jumlahHutang' => 'The jumlah hutang field is required.',
        ]); 
    }


    //HTG-S-037
    public function test_make_hutang_done_fail_field_nama_unfilled(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.dashboard'));

        $data = [
            'nama' => '',
            'catatan' => 'hutang buat bahan',
            'jumlahHutang' => 1000000,
            'tanggal_lunas' => 2024-10-30,
            'tenggat_waktu' => null,
            'nominal' => null,
            'status' => 1,
        ];

        // Send POST request
        $response = $this->post(route('hutang.store'), $data);
        // dd($response->status(), $response->getContent());

        // Assert status code
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'nama' => 'The nama field is required.',
        ]); 
    }

    //HTG-S-038
    public function test_make_hutang_done_fail_field_tanggal_lunas_unfilled(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.dashboard'));

        $data = [
            'nama' => 'Bank BRI',
            'catatan' => 'hutang buat bahan',
            'jumlahHutang' => 1000000,
            'tanggal_lunas' => '',
            'tenggat_waktu' => null,
            'nominal' => null,
            'status' => 1,
        ];

        // Send POST request
        $response = $this->post(route('hutang.store'), $data);
        // dd($response->status(), $response->getContent());

        // Assert status code
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'tanggal_lunas' => 'The tanggal lunas field is required.',
        ]); 
    }

    //HTG-S-039
    public function test_make_hutang_done_fail_field_jumlahHutang_unfilled(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.dashboard'));

        $data = [
            'nama' => 'Bank BRI',
            'catatan' => 'hutang buat bahan',
            'jumlahHutang' => '',
            'tanggal_lunas' => 2024-10-30,
            'tenggat_waktu' => null,
            'nominal' => null,
            'status' => 1,
        ];

        // Send POST request
        $response = $this->post(route('hutang.store'), $data);
        // dd($response->status(), $response->getContent());

        // Assert status code
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'jumlahHutang' => 'The jumlah hutang field is required.'
        ]); 
    }

    //HTG-S-040
    public function test_make_hutang_done_success_field_catatan_unfilled(): void
    {
        // Authentication
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.dashboard'));

        // Prepare hutang data
        $data = [
            'nama' => 'Bank BRI',
            'catatan' => '',
            'jumlahHutang' => 1000000,
            'tanggal_lunas' => '2024-10-30',
            'tenggat_waktu' => null,
            'nominal' => null,
            'status' => 1,
        ];

        // Send POST request with withoutMiddleware()
        $response = $this->post(route('hutang.store'), $data);

        // Optional debugging if needed
        // if ($response->status() !== 302) {
        //     dump($response->status(), $response->getContent());
        // }

        // Assert redirect
        $response->assertStatus(302);
        $response->assertRedirect(route('hutang.index'));

        // Check database
        $this->assertDatabaseHas('hutangs', [
            'nama' => $data['nama'],
            'catatan' => $data['catatan'],
            'status' => $data['status'],
            'jumlah_hutang' => $data['jumlahHutang'],
            'tenggat_waktu' => $data['tenggat_waktu'],
            'tanggal_lunas' => $data['tanggal_lunas'],
        ]); 
    }

    //HTG-S-041
    public function test_make_hutang_done_fail_all_field_unfilled(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.dashboard'));

        $data = [
            'nama' => '',
            'catatan' => '',
            'jumlahHutang' => '',
            'tanggal_lunas' => '',
            'tenggat_waktu' => null,
            'nominal' => null,
            'status' => 1,
        ];

        // Send POST request
        $response = $this->post(route('hutang.store'), $data);
        // dd($response->status(), $response->getContent());

        // Assert status code
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'nama' => 'The nama field is required.',
            'jumlahHutang' => 'The jumlah hutang field is required.',
            'tanggal_lunas' => 'The tanggal lunas field is required.',
        ]); 
    }

    //HTG-S-042
    public function test_make_hutang_done_fail_field_nama_invalid(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.dashboard'));

        $data = [
            'nama' => '$$!@#!@asd',
            'catatan' => 'hutang buat bahan',
            'jumlahHutang' => 1000000,
            'tanggal_lunas' => 2024-10-30,
            'tenggat_waktu' => null,
            'nominal' => 100000,
            'status' => 1,
        ];

        // Send POST request
        $response = $this->post(route('hutang.store'), $data);
        // dd($response->status(), $response->getContent());

        // Assert status code
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'nama' => 'Nama hanya boleh mengandung huruf dan spasi.'
        ]); 
    }

    //HTG-S-043
    public function test_make_hutang_done_fail_field_jumlahHutang_invalid(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.dashboard'));

        $data = [
            'nama' => 'Bank BRI',
            'catatan' => 'hutang buat bahan',
            'jumlahHutang' => -1,
            'tanggal_lunas' => 2024-10-30,
            'tenggat_waktu' => null,
            'nominal' => 100000,
            'status' => 1,
        ];

        // Send POST request
        $response = $this->post(route('hutang.store'), $data);
        // dd($response->status(), $response->getContent());

        // Assert status code
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'jumlahHutang' => 'Jumlah hutang harus berupa angka.'
        ]); 
    }

    //HTG-S-044
    public function test_make_hutang_done_fail_field_tanggal_lunas_invalid(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.dashboard'));

        $data = [
            'nama' => 'Bank BRI',
            'catatan' => 'hutang buat bahan',
            'jumlahHutang' => 1000000,
            'tanggal_lunas' => 2024-30-10,
            'tenggat_waktu' => null,
            'nominal' => null,
            'status' => 1,
        ];

        // Send POST request
        $response = $this->post(route('hutang.store'), $data);
        // dd($response->status(), $response->getContent());

        // Assert status code
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'tanggal_lunas' => 'Tanggal lunas harus berupa tanggal dengan format YYYY-MM-DD.'
        ]); 
    }

     //ERORR
    //HTG-S-045
    public function test_edit_hutang_done_success(): void
    {
        // Autentikasi pengguna
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
    
        $updatedData = [
            'nama' => 'Bank XYZ', // Nama baru
            'catatan' => 'Catatan hutang diperbarui',
            'status' => 0, // Status tetap 0 (belum lunas)
            'jumlahHutang' => 600000, // Perhatikan nama field sesuai dengan validasi
            'tenggat_waktu' => '2024-10-31', // Tanggal tenggat baru
            'tanggal_lunas' => null,
        ];
    
        // Kirim request PUT ke rute update
        $response = $this->patch(route('hutang.update', $hutang->id), $updatedData);
    
        // Pastikan response sukses (biasanya 302 untuk redirect setelah edit)
        $response->assertStatus(302);
        $response->assertRedirect(route('hutang.index'));
        // dd(Hutang::find($hutang->id));

        // Pastikan data di database telah diperbarui
        $this->assertDatabaseHas('hutangs', [
            'id' => $hutang->id,
            'nama' => 'Bank XYZ', // Periksa nama baru
            'catatan' => 'Catatan hutang diperbarui',
            'status' => 0, // Status masih 0
            'jumlah_hutang' => 600000, // Periksa jumlah hutang baru
            'tenggat_waktu' => '2024-10-31',
            'tanggal_lunas' => null, // Pastikan tanggal lunas tetap null
        ]);
    
        // Pastikan tidak ada cicilan terkait hutang ini di database
        $this->assertDatabaseMissing('cicilan_hutangs', [
            'hutangId' => $hutang->id,
        ]);
    }
 
    //HTG-S-046
    public function test_edit_hutang_done_to_undone_success(): void
    {
        // Autentikasi pengguna
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

        $updatedData = [
            'nama' => 'Bank XYZ', // Nama baru
            'catatan' => 'Catatan hutang diperbarui',
            'status' => 1, // Status 1 (done)
            'jumlahHutang' => 600000, 
            'tenggat_waktu' => '2024-10-31', // Tanggal tenggat baru
            'tanggal_lunas' => null,
        ];

        // Kirim request PUT ke rute update
        $response = $this->patch(route('hutang.update', $hutang->id), $updatedData);

        // Pastikan response sukses (biasanya 302 untuk redirect setelah edit)
        $response->assertStatus(302);
        $response->assertRedirect(route('hutang.index'));
        // dd(Hutang::find($hutang->id));

        // Pastikan data di database telah diperbarui
        $this->assertDatabaseHas('hutangs', [
            'id' => $hutang->id,
            'nama' => 'Bank XYZ', // Periksa nama baru
            'catatan' => 'Catatan hutang diperbarui',
            'status' => 0, // Status masih 0
            'jumlah_hutang' => 600000, // Periksa jumlah hutang baru
            'tenggat_waktu' => '2024-10-31',
            'tanggal_lunas' => null, // Pastikan tanggal lunas tetap null
        ]);

        // Pastikan tidak ada cicilan terkait hutang ini di database
        $this->assertDatabaseMissing('cicilan_hutangs', [
            'hutangId' => $hutang->id,
        ]);
    }

    //HTG-S-047
    public function test_delete_hutang_done_success(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $hutang = Hutang::create([
            'nama' => 'Bank ABC',
            'catatan' => 'Catatan hutang',
            'status' => 1,
            'jumlah_hutang' => 500000,
            'tenggat_waktu' => '2024-10-30',
            'tanggal_lunas' => null,
        ]);

        // Pastikan data berhasil dibuat
        $this->assertDatabaseHas('hutangs', [
            'id' => $hutang->id,
            'nama' => 'Bank ABC',
        ]);

        // Kirim request untuk menghapus data
        $response = $this->delete(route('hutang.destroy', $hutang->id));
        // dd($response->getContent());
        $response->assertStatus(302);
        $response->assertRedirect(route('hutang.index'));

        // Pastikan data tidak ada lagi di database
        $this->assertSoftDeleted('hutangs', [
            'id' => $hutang->id,
        ]);

        $deletedHutang = Hutang::withTrashed()->find($hutang->id);
        $this->assertNotNull($deletedHutang->deleted_at, 'Kolom deleted_at harus terisi setelah soft delete.');
    }

    //HTG-S-048
    public function test_see_detail_hutang_done_success(): void
    {
        // Autentikasi pengguna
        $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
    
        // Buat data hutang
        $hutang = Hutang::create([
            'nama' => 'Bank asd',
            'catatan' => 'Catatan hutang',
            'status' => 1,
            'jumlah_hutang' => 500000,
            'tanggal_lunas' => '2024-10-30',
        ]);
    
        // Panggil metode show untuk melihat detail hutang
        $response = $this->get(route('hutang.detail', $hutang->id));
    
        // Pastikan respons sukses (biasanya 200 untuk tampilan detail)
        $response->assertStatus(200);
        // dd($response->viewData('hutangData'));

        $response->assertViewIs('pages.hutang.detail');

        // Periksa setiap atribut secara langsung
        $response->assertViewHas('hutangData');
        $viewHutangData = $response->viewData('hutangData');

        // Pastikan setiap atribut cocok
        $this->assertEquals($hutang->id, $viewHutangData->id);
        $this->assertEquals($hutang->nama, $viewHutangData->nama);
        $this->assertEquals($hutang->catatan, $viewHutangData->catatan);
        $this->assertEquals($hutang->status, $viewHutangData->status);
        $this->assertEquals($hutang->jumlah_hutang, $viewHutangData->jumlah_hutang);
        $this->assertTrue(
            Carbon::parse($hutang->tanggal_lunas)->eq(Carbon::parse($viewHutangData->tanggal_lunas))
        );    
    }
}