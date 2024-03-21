<?php

namespace Database\Seeders;

use App\Models\Preorder;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PreorderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Preorder::insert([
            'is_dp'=>true,
            'down_payment'=>50000,
            'tanggal_pembayaran_down_payment'=>Carbon::now(),
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now()
        ]);
    }
}
