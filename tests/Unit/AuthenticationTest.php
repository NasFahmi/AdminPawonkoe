<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    // use RefreshDatabase;

    /**
     * berhasil login username dan password valid
     * gagal login username vallid password salah
     * gagal login username salah password valid
     * gagal login username salah dan password salah
     * gagal login username valid password kosong
     * gagal login username kosong password valid
     * gagal login username dan password kosong
     * gagal login username valid password salah terkena limiter setelah attempt x3
     * login berhasil setelah menunggu rate limiter selesai
     */
    public function test_successful_login()
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'admin',
            'password' => 'admin',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.dashboard'));
        // Tambahkan assertion lain sesuai kebutuhan
    }
    public function test_failed_login_with_invalid_password()
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'admin',
            'password' => 'passwordsalah',
        ]);
        // dd($response->);
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'login' => 'Nama Atau Password Salah'
        ]);
        $response->assertRedirect(route('login'));
    }
    public function test_failed_login_with_invalid_username()
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'namasalah',
            'password' => 'admin',
        ]);
        // dd($response->);
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'login' => 'Nama Atau Password Salah'
        ]);
        $response->assertRedirect(route('login'));
    }
    public function test_failed_login_with_invalid_username_and_password()
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'namasalah',
            'password' => 'passwordsalah',
        ]);
        // dd($response->);
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'login' => 'Nama Atau Password Salah'
        ]);
        $response->assertRedirect(route('login'));
    
    }
    public function test_failed_login_with_empty_password()
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'admin',
            'password' => '',
        ]);
        // dd($response->);
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'password' => 'The password field is required.'
        ]);
        $response->assertRedirect(route('login'));
    }
    public function test_failed_login_with_empty_username()
    {
        $response = $this->post(route('authentication'), [
            'nama' => '',
            'password' => 'admin',
        ]);
        // dd($response->);
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'nama' => 'The nama field is required.'
        ]);
        $response->assertRedirect(route('login'));
    }
    public function test_failed_login_with_empty_username_and_password()
    {
        $response = $this->post(route('authentication'), [
            'nama' => '',
            'password' => '',
        ]);
        // dd($response);
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'nama' => 'The nama field is required.',
            'password' => 'The password field is required.'
        ]);
        $response->assertRedirect(route('login'));
    }
    public function test_failed_login_with_valid_username_and_invalid_password_trigger_rate_limiter()
    {
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
        $response->assertSessionHasErrors(['login']);
        $this->assertStringContainsString('Login Terlalu Cepat', session('errors')->first('login'));
        $response->assertRedirect(route('login'));
    }
    public function test_successful_login_after_rate_limiter_expires()
    {
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

        // Tunggu 60 detik untuk rate limiter berlaku
        sleep(65);

        // Percobaan login ke-4 seharusnya tidak memicu rate limiter
        $response = $this->post(route('authentication'), [
            'nama' => 'admin',
            'password' => 'admin',
        ]);

        $response->assertStatus(302);
        // $response->assertSessionHasErrors(['login']);
        // $this->assertStringContainsString('Login Terlalu Cepat', session('errors')->first('login'));
        $response->assertRedirect(route('admin.dashboard'));
    }
    public function test_successful_logout()
    {
        // login 
        $response = $this->post(route('authentication'), [
            'nama' => 'admin',
            'password' => 'admin',
        ]);
        // logout
        $response = $this->get(route('logout'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }
}
//! php artisan test --coverage-html coverage
