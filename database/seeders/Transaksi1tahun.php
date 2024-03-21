<?php

namespace Database\Seeders;

use App\Models\Transaksi;
use Illuminate\Database\Seeder;

class Transaksi1tahun extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pembeli_id = 1;
        $product_id = 1;
        $methode_pembayaran_id = 1;
        $jumlah = 1;
        $keterangan = "test keterangan";
        $is_Preorder = 0;
        $Preorder_id = null;
        $is_complete = 1;

        // Loop for 12 months
        for ($month = 1; $month <= 12; $month++) {
            $tanggal = now()->subMonths(12)->addMonths($month)->startOfMonth();
            $total_harga = mt_rand(100000, 5000000); // Change total_harga to a random value between 10000 and 100000

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
