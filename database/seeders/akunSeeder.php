<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AkunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            "nama" => "admin",
            "email" => "pawonkoeAdmin@gmail.com",
            "password" => Hash::make('admin')
        ]);
        $admin->assignRole('admin');

        $owner = User::create([
            "nama" => "pawonkoe",
            "email" => "pawonkoe@gmail.com",
            "password" => Hash::make('pawonkoe')
        ]);
        $owner->assignRole('superadmin');
    }
}
