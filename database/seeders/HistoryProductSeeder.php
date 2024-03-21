<?php

namespace Database\Seeders;

use App\Models\HistoryProduct;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class HistoryProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HistoryProduct::insert([
            'nama_product' => 'Product A',
            'harga' => '10000',
            'deskripsi' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sit eos ab voluptatem eaque animi. Neque nihil repudiandae officia vero sed!',
            'link_shopee' => 'link',
            'stok' => '40',
            'spesifikasi_product' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sit eos ab voluptatem eaque animi. Neque nihil repudiandae officia vero sed!',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
