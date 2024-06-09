<?php

namespace Database\Seeders;

use App\Models\JenisModal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JenisModalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JenisModal::create([
            'jenis_modal' => 'Fisik'
        ]);
        JenisModal::create([
            'jenis_modal' => 'Finansial'
        ]);
    }
}
