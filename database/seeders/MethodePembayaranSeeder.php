<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MethodePembayaran;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MethodePembayaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MethodePembayaran::insert([
            [
                'methode_pembayaran'=> 'Transfer',
            ],
            [
                'methode_pembayaran'=> 'Shopee'
            ],
            [
                'methode_pembayaran'=> 'Offline'
            ],
            [
                'methode_pembayaran'=> 'Lainnya'
            ]
        ]);
    }
}
