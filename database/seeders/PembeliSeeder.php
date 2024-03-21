<?php

namespace Database\Seeders;

use App\Models\Pembeli;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PembeliSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pembeli::insert([
            'nama'=>'NPC1',
            'email'=>'npc@npc.com',
            'alamat'=>'npc everywhere',
            'no_hp'=>'08123456789',
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);
    }
}
