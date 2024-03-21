<?php

namespace Database\Seeders;

use App\Models\Transaksi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Transaksi30Day extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ganti 'pembeli_id', 'product_id', 'methode_pembayaran_id', dll, sesuai dengan kebutuhan
        $pembeli_id = 1;
        $product_id = 1;
        $methode_pembayaran_id = 1;
        $jumlah = 1;
        $keterangan = "test keterangan";
        $is_Preorder = 0;
        $Preorder_id = null;
        $is_complete = 1;

        for ($i = 1; $i <= 30; $i++) {
            $tanggal = "2023-12-" . sprintf("%02d", $i);
            $total_harga = mt_rand(10000, 100000); // Ubah nilai total_harga menjadi acak antara 10000 dan 100000

            Transaksi::insert([
                [
                    'tanggal' => $tanggal,
                    'pembeli_id' => $pembeli_id,
                    'product_id' => $product_id,
                    'methode_pembayaran_id' => $methode_pembayaran_id,
                    'jumlah' => $jumlah,
                    'total_harga' => $total_harga,
                    'keterangan' => $keterangan,
                    'is_Preorder' => $is_Preorder,
                    'Preorder_id' => $Preorder_id,
                    'is_complete' => $is_complete,
                ]
            ]);
        }
    }
}
