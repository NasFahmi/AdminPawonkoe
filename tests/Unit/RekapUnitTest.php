<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Rekap;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class RekapUnitTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    public function setUp(): void
    {
        parent::setUp();

        // $this->withoutExceptionHandling();
        $this->artisan('db:seed');
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

    public function test_see_detail_rekap_expenses(): void
    {
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

        $response = $this->get(route('rekap.filter', ['masuk', 'semua', '11']));
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

        $response = $this->get(route('rekap.filter', ['masuk', '2024']));
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
            ],
            [
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

        $response = $this->get(route('rekap.filter', ['masuk', '2024', '11']));
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

        $response = $this->get(route('rekap.filter', ['keluar', 'semua', '11']));
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
        $response = $this->get(route('rekap.filter', ['keluar', '2024']));
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
            ],
            [
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

        $response = $this->get(route('rekap.filter', ['keluar', '2024', '11']));
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
            ],
            [
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
            ],
            [
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
            ],
            [
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
            ],
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
            ],
            [
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
            ],
            [
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
            ],
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
            ],
            [
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
            ],
            [
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
            ],
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
            ],
            [
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

    public function test_see_rekap_expenses_kategori_by_year_and_month_search(): void
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
            ],
            [
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

        $response = $this->get(route('rekap.filter', ['keluar', '2024', '11']));
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

        $response = $this->get('admin/rekap-keuangan/detail/keluar/semua/11?search=Beban');
        $response->assertOk();
        $response->assertSee('WiFi');
        $response->assertSee('Listrik');
    }



    // Fungsi untuk memasukkan data dummy Rekap
    private function insertRekapData()
    {
        Rekap::insert([
            [
                'tanggal_transaksi' => '2024-11-15',
                'sumber' => 'Modal',
                'jumlah' => 2000,
                'keterangan' => 'Modal dari Owner',
                'id_tabel_asal' => 1,
                'tipe_transaksi' => 'masuk',
            ],
            [
                'tanggal_transaksi' => '2024-11-15',
                'sumber' => 'Pendapatan',
                'jumlah' => 3000,
                'keterangan' => 'Pendapatan Bulanan',
                'id_tabel_asal' => 2,
                'tipe_transaksi' => 'masuk',
            ],
            [
                'tanggal_transaksi' => '2024-11-15',
                'sumber' => 'Beban',
                'jumlah' => 1000,
                'keterangan' => 'Pembayaran Listrik',
                'id_tabel_asal' => 3,
                'tipe_transaksi' => 'keluar',
            ],
            [
                'tanggal_transaksi' => '2024-04-15',
                'sumber' => 'Piutang',
                'jumlah' => 1500,
                'keterangan' => 'Piutang Toko A',
                'id_tabel_asal' => 4,
                'tipe_transaksi' => 'masuk',
            ],
            // Data transaksi lainnya bisa ditambahkan sesuai kebutuhan
        ]);
    }
    public function test_filter_rekap_tahun()
    {
        // Autentikasi terlebih dahulu jika diperlukan
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);

        $tahun = 2024;
        $this->insertRekapData();

        // Memanggil API untuk filter berdasarkan tahun (bulan = '-')
        $response = $this->json('post', route('chart.filter'), [
            'tahun' => $tahun,
            'bulan' => '-',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'saldoAkhir',
                'date'
            ])
            ->assertJson([
                'saldoAkhir' => [
                ]
            ]);

    }

    public function test_filter_rekap_by_year_and_month()
    {
        $response = $this->post(route('authentication'), [
            'nama' => 'pawonkoe',
            'password' => 'pawonkoe',
        ]);
        // Prepare mock data for Rekap
        Carbon::setTestNow(Carbon::create(2024, 11, 16)); // Set current date

        $this->insertRekapData();
        // Simulate a request with year and month filter
        $response = $this->postJson(route('chart.filter', [
            'tahun' => 2024,
            'bulan' => 11,
        ]));

        // Assert the response is successful and contains the expected structure
        $response->assertStatus(200)
            ->assertJsonStructure([
                'saldoAkhir',
                'date'
            ])
            ->assertJson([
                'saldoAkhir' => [
                ]
            ]);
    }
    // Test untuk filter berdasarkan tahun dan bulan


}


