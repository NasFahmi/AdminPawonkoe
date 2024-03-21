<?php

namespace Database\Seeders;

use App\Models\Transaksi;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Transaksi::insert([
            'tanggal'=>Carbon::now(),
            'pembeli_id'=>1,
            'product_id'=>1,
            'methode_pembayaran_id'=>1,
            'jumlah'=>4,
            'total_harga'=>100000,
            'keterangan'=> 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quod, recusandae.',
            'is_Preorder'=>true,
            'Preorder_id'=>1,
            'is_complete'=>false,
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);
    }
}
