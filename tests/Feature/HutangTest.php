<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class HutangTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use WithoutMiddleware;

    // HTG-BS-001
    public function test_make_hutang_success_status_undone(): void
    {
        $user = User::findOrFail(2); // Pastikan user ini ada
        $this->actingAs($user);

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
        $response = $this->post(route('hutang.store'), $data);
        // dd($response->status(), $response->getContent());

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
    public function test_make_hutang_fail_field_nama(): void
    {
        $user = User::findOrFail(2); // Pastikan user ini ada
        $this->actingAs($user);

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
        $response = $this->post(route('hutang.store'), $data);
        // dd($response->status(), $response->getContent());

        // Assert status code
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'jumlahHutang' => 'The jumlah hutang field is required.',
            'nominal' => 'The nominal field is required.',
            'tenggat_waktu' => 'The tenggat waktu field is required.'
        ]); 
        $response->assertRedirect(route('hutang.store'));
    }

    // HTG-BS-003
    public function test_make_hutang_fail_field_nominal(): void
    {
        $user = User::findOrFail(2); // Pastikan user ini ada
        $this->actingAs($user);

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
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'nama' => 'The nama field is required.',
            'jumlahHutang' => 'The jumlah hutang field is required.',
            'tenggat_waktu' => 'The tenggat waktu field is required.'
        ]); 
        $response->assertRedirect(route('hutang.create'));
    }

    // HTG-BS-004
    public function test_make_hutang_fail_field_jumlahHutang(): void
    {
        $user = User::findOrFail(2); // Pastikan user ini ada
        $this->actingAs($user);

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

        // Assert status code
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'nama' => 'The nama field is required.',
            'nominal' => 'The nominal field is required.',
            'tenggat_waktu' => 'The tenggat waktu field is required.'
        ]); 
        $response->assertRedirect(route('hutang.store'));
    }

    // HTG-BS-005
    public function test_make_hutang_fail_field_catatan(): void
    {
        $user = User::findOrFail(2); // Pastikan user ini ada
        $this->actingAs($user);

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
        $response->assertRedirect(route('hutang.store'));
    }

    //HTG-BS-006
    public function test_make_hutang_fail_field_tenggat_waktu(): void
    {
        $user = User::findOrFail(2); // Pastikan user ini ada
        $this->actingAs($user);

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
        $response->assertRedirect(route('hutang.store'));
    }

    //HTG-BS-007
    public function test_make_hutang_fail_field_nama_and_catatan(): void
    {
        $user = User::findOrFail(2); // Pastikan user ini ada
        $this->actingAs($user);

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
        $response->assertRedirect(route('hutang.store'));
    }

    //HTG-BS-008
    public function test_make_hutang_fail_field_nama_and_nominal(): void
    {
        $user = User::findOrFail(2); // Pastikan user ini ada
        $this->actingAs($user);

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
            'nama' => 'The nama field is required.',
            'nominal' => 'The nominal field is required.',
            'jumlahHutang' => 'The jumlah hutang field is required.',
            'tenggat_waktu' => 'The tenggat waktu field is required.'
        ]); 
        $response->assertRedirect(route('hutang.store'));
    }

    //HTG-BS-009
    public function test_make_hutang_fail_field_nama_and_jumlahHutang(): void
    {
        $user = User::findOrFail(2); // Pastikan user ini ada
        $this->actingAs($user);

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
        $response->assertRedirect(route('hutang.store'));
    }

    //HTG-BS-010
    public function test_make_hutang_fail_field_nama_and_tenggat_waktu(): void
    {
        $user = User::findOrFail(2); // Pastikan user ini ada
        $this->actingAs($user);

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
            'tenggat_waktu' => 'The tenggat waktu field is required.'
        ]); 
        $response->assertRedirect(route('hutang.store'));
    }

    //HTG-BS-011
    public function test_make_hutang_fail_field_nama_catatan_and_nominal(): void
    {
        $user = User::findOrFail(2); // Pastikan user ini ada
        $this->actingAs($user);

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
        $response->assertRedirect(route('hutang.store'));
    }

    //HTG-BS-012
    public function test_make_hutang_fail_field_nama_catatan_and_jumlahHutang(): void
    {
        $user = User::findOrFail(2); // Pastikan user ini ada
        $this->actingAs($user);

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
        $response->assertRedirect(route('hutang.store'));
    }

    //HTG-BS-013
    public function test_make_hutang_fail_field_nama_catatan_and_tenggat_waktu(): void
    {
        $user = User::findOrFail(2); // Pastikan user ini ada
        $this->actingAs($user);

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
        $response->assertRedirect(route('hutang.store'));
    }

    //HTG-BS-014
    public function test_make_hutang_fail_field_nama_unfilled(): void
    {
        $user = User::findOrFail(2); // Pastikan user ini ada
        $this->actingAs($user);

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
        $response->assertRedirect(route('hutang.store'));
    }

    //HTG-BS-015
    public function test_make_hutang_fail_field_nominal_unfilled(): void
    {
        $user = User::findOrFail(2); // Pastikan user ini ada
        $this->actingAs($user);

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
        $response->assertRedirect(route('hutang.store'));
    }

    //HTG-BS-016
    public function test_make_hutang_fail_field_jumlahHutang_unfilled(): void
    {
        $user = User::findOrFail(2); // Pastikan user ini ada
        $this->actingAs($user);

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
        $response->assertRedirect(route('hutang.store'));
    }

    //HTG-BS-017
    public function test_make_hutang_fail_field_tenggat_waktu_unfilled(): void
    {
        $user = User::findOrFail(2); // Pastikan user ini ada
        $this->actingAs($user);

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
        $response->assertRedirect(route('hutang.store'));
    }

    //HTG-BS-018
    public function test_make_hutang_fail_all_field_unfilled(): void
    {
        $user = User::findOrFail(2); // Pastikan user ini ada
        $this->actingAs($user);

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
        $response->assertRedirect(route('hutang.store'));
    }

    //HTG-BS-019
    public function test_make_hutang_fail_field_nama_invalid(): void
    {
        $user = User::findOrFail(2); // Pastikan user ini ada
        $this->actingAs($user);

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
            'nama' => 'The nama field is invalid.'
        ]); 
        $response->assertRedirect(route('hutang.store'));
    }

    //HTG-BS-020
    public function test_make_hutang_fail_field_nominal_invalid(): void
    {
        $user = User::findOrFail(2); // Pastikan user ini ada
        $this->actingAs($user);

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
            'nominal' => 'The nominal field is invalid.'
        ]); 
        $response->assertRedirect(route('hutang.store'));
    }

    //HTG-BS-021
    public function test_make_hutang_fail_field_jumlahHutang_invalid(): void
    {
        $user = User::findOrFail(2); // Pastikan user ini ada
        $this->actingAs($user);

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
            'jumlahHutang' => 'The jumlah hutang field is invalid.'
        ]); 
        $response->assertRedirect(route('hutang.store'));
    }

    //HTG-BS-022
    public function test_make_hutang_fail_field_tenggat_waktu_invalid(): void
    {
        $user = User::findOrFail(2); // Pastikan user ini ada
        $this->actingAs($user);

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
            'tenggat_waktu' => 'The tenggat waktu field is invalid.'
        ]); 
        $response->assertRedirect(route('hutang.store'));
    }

    //HTG-S-023
    public function test_make_hutang_success_status_done(): void
    {
        $user = User::findOrFail(2); // Pastikan user ini ada
        $this->actingAs($user);

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
        // dd($response->status(), $response->getContent());

        $response->assertStatus(302);
         
        $response->assertRedirect(route('hutang.index'));
    }
}
