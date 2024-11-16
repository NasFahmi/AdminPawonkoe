<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use App\Models\Hutang;
use App\Models\CicilanHutang;
use App\Models\Rekap;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class HutangUnitTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    public function setUp(): void
    {
        parent::setUp();

        // $this->withoutExceptionHandling();
        $this->artisan('db:seed');
    }

    // HTG-BS-001
    public function test_make_hutang_undone_success(): void
    {
        $response = $this->post(route('authentication'), [
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

    // HTG-BS-002
    public function test_make_hutang_undone_fail_field_nama(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $data = [
            'nama' => 'Bank BRI',
            'catatan' => '',
            'jumlahHutang' => '',
            'tanggal_lunas' => null,
            'tenggat_waktu' => '',
            'nominal' => '',
            'status' => 0,
        ];

        $response = $this->post(route('hutang.store'), $data);

        $response->assertSessionHasErrors([
            'jumlahHutang' => 'The jumlah hutang field is required.',
            'nominal' => 'The nominal field is required.',
            'tenggat_waktu' => 'The tenggat waktu field is required.'
        ]);
    }

    //HTG-BS-007
    public function test_make_hutang_undone_fail_field_nama_and_catatan(): void
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

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

        // Assert status code
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'nominal' => 'The nominal field is required.',
            'jumlahHutang' => 'The jumlah hutang field is required.',
            'tenggat_waktu' => 'The tenggat waktu field is required.'
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
            'tenggat_waktu' => 2024 - 10 - 30,
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
            'tenggat_waktu' => 2024 - 10 - 30,
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
            'tenggat_waktu' => 2024 - 10 - 30,
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
            'tenggat_waktu' => 2002 - 20 - 30,
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

    //HTG-BS-023
    public function test_edit_hutang_undone_success(): void
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

        $response->assertStatus(302);
        $response->assertRedirect(route('hutang.index'));

        $updatedHutang = Hutang::find($id);
        $this->assertEquals('Bank XYZ', $updatedHutang->nama);
        $this->assertEquals(600000, $updatedHutang->jumlah_hutang);

        $updatedCicilan = CicilanHutang::where('hutangId', $id)->first();
        $this->assertNotNull($updatedCicilan);
        $this->assertEquals(60000, $updatedCicilan->nominal);
    }

    //HTG-BS-024
    public function test_edit_hutang_undone_to_done_success(): void
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
        CicilanHutang::create([
            'nominal' => 50000,
            'hutangId' => $hutang->id,
        ]);

        $updatedData = [
            'nama' => 'Bank XYZ',
            'catatan' => 'Catatan hutang diperbarui',
            'status' => 1,
            'jumlahHutang' => 600000,
            'tenggat_waktu' => null,
            'tanggal_lunas' => '2024-12-31',
            'nominal' => 60000,
        ];

        $response = $this->patch(route('hutang.update', $hutang->id), $updatedData);

        $response->assertStatus(302);
        $response->assertRedirect(route('hutang.index'));

        $hutang->refresh();
        $updatedHutang = Hutang::find($hutang->id);

        $this->assertEquals(1, $updatedHutang->status);

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

        $this->assertDatabaseHas('hutangs', [
            'id' => $hutang->id,
            'nama' => 'Bank ABC',
        ]);

        $response = $this->delete(route('hutang.destroy', $hutang->id));
        $response->assertStatus(302);
        $response->assertRedirect(route('hutang.index'));

        $this->assertSoftDeleted('hutangs', [
            'id' => $hutang->id,
        ]);

        $deletedHutang = Hutang::withTrashed()->find($hutang->id);
        $this->assertNotNull($deletedHutang->deleted_at, 'Kolom deleted_at harus terisi setelah soft delete.');
    }

    //HTG-BS-026
    public function test_see_detail_hutang_undone_success(): void
    {
        $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $hutang = Hutang::create([
            'nama' => 'Bank asd',
            'catatan' => 'Catatan hutang',
            'status' => 0,
            'jumlah_hutang' => 500000,
            'tenggat_waktu' => '2024-10-30',
            'nominal' => 50000,
        ]);

        $response = $this->get(route('hutang.detail', $hutang->id));

        $response->assertStatus(200);
        $response->assertViewIs('pages.hutang.detail');

        $response->assertViewHas('hutangData');
        $viewHutangData = $response->viewData('hutangData');

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
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.dashboard'));

        $hutang = Hutang::create([
            'nama' => 'Bank ABC',
            'catatan' => 'Catatan hutang',
            'jumlah_hutang' => 100000,
            'status' => 0,
            'tenggat_waktu' => '2024-11-30 23:59:59',
        ]);

        CicilanHutang::create([
            'nominal' => 50000,
            'hutangId' => $hutang->id,
        ]);

        $response = $this->post(route('cicilan.store', $hutang->id), [
            'nominal' => 50000,
        ]);

        $response->assertSessionHas('success', 'Data Berhasil Disimpan');

        $hutang->refresh();
        $this->assertEquals(1, $hutang->status);
        $this->assertNotNull($hutang->tanggal_lunas);
    }


    //HTG-BS-027
    public function test_paying_hutang_undone_success(): void
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
            'jumlah_hutang' => 100000,
            'status' => 0,
            'tenggat_waktu' => '2024-11-30 23:59:59',
        ]);

        CicilanHutang::create([
            'nominal' => 50000,
            'hutangId' => $hutang->id,
        ]);

        $response = $this->post(route('cicilan.store', $hutang->id), [
            'nominal' => 20000,
        ]);

        $response->assertSessionHas('success', 'Data Berhasil Disimpan');

        $hutang->refresh();
        $this->assertEquals(0, $hutang->status);
    }


    //HTG-S-028
    public function test_make_hutang_done_success(): void
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
            'tanggal_lunas' => '2024-10-30',
            'tenggat_waktu' => null,
            'nominal' => null,
            'status' => 1,
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

        $response = $this->withoutMiddleware()
            ->post(route('hutang.store'), $data);

        $response->assertSessionHasErrors([
            'jumlahHutang' => 'The jumlah hutang field is required.',
            'tanggal_lunas' => 'The tanggal lunas field is required.'
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

        $response = $this->post(route('hutang.store'), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'jumlahHutang' => 'The jumlah hutang field is required.',
            'tanggal_lunas' => 'The tanggal lunas field is required.'
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
            'tanggal_lunas' => 2024 - 10 - 30,
            'tenggat_waktu' => null,
            'nominal' => null,
            'status' => 1,
        ];

        $response = $this->post(route('hutang.store'), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'nama' => 'The nama field is required.',
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

        $response = $this->post(route('hutang.store'), $data);

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
            'tanggal_lunas' => 2024 - 10 - 30,
            'tenggat_waktu' => null,
            'nominal' => 100000,
            'status' => 1,
        ];

        $response = $this->post(route('hutang.store'), $data);

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
            'tanggal_lunas' => 2024 - 10 - 30,
            'tenggat_waktu' => null,
            'nominal' => 100000,
            'status' => 1,
        ];

        $response = $this->post(route('hutang.store'), $data);

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

        $data = [
            'nama' => 'Bank BRI',
            'catatan' => 'hutang buat bahan',
            'jumlahHutang' => 1000000,
            'tanggal_lunas' => 2024 - 30 - 10,
            'tenggat_waktu' => null,
            'nominal' => null,
            'status' => 1,
        ];

        $response = $this->post(route('hutang.store'), $data);

        $response->assertSessionHasErrors([
            'tanggal_lunas' => 'Tanggal lunas harus berupa tanggal dengan format YYYY-MM-DD.'
        ]);
    }

    //ERORR
    //HTG-S-045
    public function test_edit_hutang_done_success(): void
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

        $updatedData = [
            'nama' => 'Bank XYZ',
            'catatan' => 'Catatan hutang diperbarui',
            'status' => 0,
            'jumlahHutang' => 600000,
            'tenggat_waktu' => '2024-10-31',
            'tanggal_lunas' => null,
        ];

        $response = $this->patch(route('hutang.update', $hutang->id), $updatedData);
        $response->assertStatus(302);
        $response->assertRedirect(route('hutang.index'));

        $this->assertDatabaseHas('hutangs', [
            'id' => $hutang->id,
            'nama' => 'Bank XYZ',
            'catatan' => 'Catatan hutang diperbarui',
            'status' => 0,
            'jumlah_hutang' => 600000,
            'tenggat_waktu' => '2024-10-31',
            'tanggal_lunas' => null,
        ]);

        $this->assertDatabaseMissing('cicilan_hutangs', [
            'hutangId' => $hutang->id,
        ]);
    }

    //HTG-S-046
    public function test_edit_hutang_done_to_undone_success(): void
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
            'tenggat_waktu' => null,
            'tanggal_lunas' => '2024-10-30',
        ]);

        $updatedData = [
            'nama' => 'Bank XYZ',
            'catatan' => 'Catatan hutang diperbarui',
            'status' => 0,
            'jumlahHutang' => 600000, // Perbaikan kolom
            'tenggat_waktu' => 2024 - 11 - 31,
            'tanggal_lunas' => null,
            'nominal' => 100000
        ];

        // dd(route('hutang.update', ['hutang' => $hutang->id]));

        $this->patch(route('hutang.update', ['hutang' => $hutang->id]), $updatedData);
        // dd($response->getContent());
        $this->assertDatabaseHas('hutangs', [
            'id' => $hutang->id,
            'nama' => 'Bank XYZ',
            'catatan' => 'Catatan hutang diperbarui',
            'status' => 0,
            'jumlah_hutang' => 600000,
            'tanggal_lunas' => null,
        ]);

        $this->assertDatabaseHas('cicilan_hutangs', [
            'nominal' => 100000
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

        $this->assertDatabaseHas('hutangs', [
            'id' => $hutang->id,
            'nama' => 'Bank ABC',
        ]);

        $response = $this->delete(route('hutang.destroy', $hutang->id));
        $response->assertStatus(302);
        $response->assertRedirect(route('hutang.index'));

        $this->assertSoftDeleted('hutangs', [
            'id' => $hutang->id,
        ]);

        $deletedHutang = Hutang::withTrashed()->find($hutang->id);
        $this->assertNotNull($deletedHutang->deleted_at, 'Kolom deleted_at harus terisi setelah soft delete.');
    }

    //HTG-S-048
    public function test_see_detail_hutang_done_success(): void
    {
        $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        // $this->withoutMiddleware();

        $hutang = Hutang::create([
            'nama' => 'Bank asd',
            'catatan' => 'Catatan hutang',
            'status' => 1,
            'jumlah_hutang' => 500000,
            'tanggal_lunas' => '2024-10-30',
        ]);

        $response = $this->get(route('hutang.detail', $hutang->id));
        // dd($response);
        $response->assertStatus(200);
        $response->assertViewIs('pages.hutang.detail');

        $response->assertViewHas('hutangData');
        $viewHutangData = $response->viewData('hutangData');

        $this->assertEquals($hutang->id, $viewHutangData->id);
        $this->assertEquals($hutang->nama, $viewHutangData->nama);
        $this->assertEquals($hutang->catatan, $viewHutangData->catatan);
        $this->assertEquals($hutang->status, $viewHutangData->status);
        $this->assertEquals($hutang->jumlah_hutang, $viewHutangData->jumlah_hutang);
        $this->assertTrue(
            Carbon::parse($hutang->tanggal_lunas)->eq(Carbon::parse($viewHutangData->tanggal_lunas))
        );
    }
    public function test_see_home_page_hutang()
    {
        $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        $response = $this->get(route('hutang.index'));
        $response->assertStatus(200);
        $response->assertViewIs('pages.hutang.index');
    }

    public function test_search_home_page_hutang_by_name()
    {
        $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        Hutang::create([
            'nama' => 'Bank ABC',
            'catatan' => 'Catatan hutang',
            'status' => 0,
            'jumlah_hutang' => 500000,
            'tenggat_waktu' => '2024-10-30',
            'tanggal_lunas' => null,
        ]);

        Hutang::create([
            'nama' => 'KSP ABC',
            'catatan' => 'Catatan hutang',
            'status' => 0,
            'jumlah_hutang' => 500000,
            'tenggat_waktu' => '2024-10-30',
            'tanggal_lunas' => null,
        ]);

        $response = $this->get('admin/hutang?search=Bank');

        $response->assertViewIs('pages.hutang.index');
        $response->assertSee('Bank ABC');
    }

    public function test_see_form_create_hutang_status_done()
    {
        $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        $response = $this->withoutMiddleware()->get(route('hutang.create', 1));

        $response->assertSee('Tanggal Lunas');
    }

    public function test_see_form_create_hutang_status_undone()
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        $response = $this->withoutMiddleware()->get(route('hutang.create', ['status' => '0']));

        $response->assertSee('Tanggal Lunas');
    }

    public function test_see_edit_form_hutang()
    {
        $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        $hutang = Hutang::create([
            'nama' => 'Bank asd',
            'catatan' => 'Catatan hutang',
            'status' => 1,
            'jumlah_hutang' => 500000,
            'tanggal_lunas' => '2024-10-30',
        ]);

        $response = $this->withoutMiddleware()->get(route('hutang.edit', $hutang->id));
        // $response->assertStatus(200);
        $response->assertSee('Edit Hutang');
    }

    public function test_form_cicil_hutang()
    {
        $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        $hutang = Hutang::create([
            'nama' => 'Bank asd',
            'catatan' => 'Catatan hutang',
            'status' => 1,
            'jumlah_hutang' => 500000,
            'tenggat_waktu' => '2024-10-30',
        ]);
        $response = $this->withoutMiddleware()->get(route('cicilan.create', $hutang->id));
        // $response->assertStatus(200);
        $response->assertSee('Tambah Cicilan Hutang');
    }

    public function test_nominal_lebih_dari_jumlah_hutang()
    {
        $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        $data = [
            'nama' => 'Bank asd',
            'catatan' => 'Catatan hutang',
            'jumlahHutang' => 500000,
            'tanggal_lunas' => null,
            'tenggat_waktu' => '2024-10-30',
            'nominal' => 1000000,
            'status' => 0,
        ];
        $response = $this->withoutMiddleware()->post(route('hutang.store'), $data);
        // dd($response->getContent());
        $response->assertSessionHasErrors([
            'nominal' => 'Nominal cicilan awal tidak boleh lebih dari jumlah hutang.',
        ]);
    }

}

