<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class PreoderTest extends TestCase
{
    /**
     user berhasil show semua data preorder
    user dapat mencari preroder berdasarkan nama product
    user dapat mencari preoder berdasarkan nama tanggal
    user dapat melihat detail dari preoder belum selesai 
    user dapat melihat detail dari preoder selesai 
    user dapat membuat preorder
    user dapat membuat proder menggunakan methode pembayaran transfer
    user dapat membuat preoder menggunakan methode pembayaran shoppe
    user dapat membuat preoder menggunakan methode pembayaran offline
    user dapat membuat preoder menggunakan methode pembayaran lainnya
    gagal membuat preoder ketika field tanggal kosong
    Total Harga Terhitung Otomatis Berdasarkan Jumlah Produk dan Harga Satuan
    gagal membuat preoder ketika tanggal DP lebih dari tanggal yang diinputkan
    gagal membuat preoder ketika field tanggalDP kosong
    gagal membuat preoder ketika field jumlahDP kosong
    validasi jumlah DP tidak boleh lebih dari total harga
    gagal membuat DP ketika field nama kosong
    gagal membuat DP ketika field email kosong
    gagal membuat DP ketika field alamat kosong
    gagal membuat DP ketika field telepon kosong
    gagal membua preoder ketika product stok sedang kosong
    validasi field jumlah tidak boleh 0 ataupun -1
    validasi field jumlah product tidak boleh melebihi dari stok yang tersedia
    field jumlah DP tidak boleh 0 ataupun -1
    Gagal Membuat Preorder Ketika Total Harga Tidak Sesuai dengan Perhitungan Otomatis
    superadmin/owder dapat mengedit status preoder yang belum selesai ke selesai
    gagal ke halaman edit transaksi ketika login menggunaakn role admin
    gagal update transaksi ketika login menggunaakn role admin
     */
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();

        // Refresh database and run all seeders
        $this->artisan('db:seed');
    }
    public function test_succesfull_get_preoder()
    {

    }
    public function test_succesfull_get_preoder_by_name_product()
    {

    }
    public function test_succesfull_get_preoder_by_date()
    {

    }

    public function test_succesfull_get_undone_preoder_detail()
    {

    }
    public function test_succesfull_get_done_preoder_detail()
    {

    }

    public function test_user_can_create_preorder()
    {

    }

    public function test_user_can_create_preorder_transfer()
    {

    }

    public function test_user_can_create_preorder_shoppe()
    {

    }

    public function test_user_can_create_preorder_offline()
    {
    }

    public function test_user_can_create_preorder_other_method()
    {
    }
    public function test_user_cannot_create_preoder_with_empty_date()
    {

    }
    public function test_validate_otomatic_calculate_total_harga()
    {

    }
    public function test_user_cannot_create_preoder_tanggalDP_more_than_tanggal()
    {

    }
    public function test_user_cannot_create_preoder_with_empty_tanggaldp()
    {

    }
    public function test_user_cannot_create_preoder_with_empty_jumlahdp()
    {

    }
    public function test_validate_jumlahdp_cannot_more_than_total_harga()
    {

    }
    public function test_user_cannot_create_preoder_with_empty_name()
    {

    }
    public function test_user_cannot_create_preoder_with_empty_email()
    {

    }
    public function test_user_cannot_create_preoder_with_empty_address()
    {

    }
    public function test_user_cannot_create_preoder_with_empty_phone()
    {

    }
    public function test_user_cannot_create_preoder_with_empty_product_stock()
    {

    }
    public function test_validate_jumlah_cannot_zero_or_minus()
    {

    }
    public function test_validate_jumlah_product_cannot_more_than_stock()
    {

    }
    public function test_validate_jumlahdp_cannot_zero_or_minus()
    {

    }
    public function test_user_cannot_create_preoder_with_not_equal_total_harga()
    {

    }
    public function test_superadmin_can_edit_preoder()
    {

    }
    public function test_user_cannot_edit_preoder()
    {

    }
    public function test_user_cannot_update_preoder()
    {

    }


}
