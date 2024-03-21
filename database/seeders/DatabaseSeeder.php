<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Product;
use App\Models\Transaksi;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call(RolePermissionSeeder::class);
        $this->call(akunSeeder::class);
        // $this->call(PembeliSeeder::class);
        $this->call(MethodePembayaranSeeder::class);
        // $this->call(PreorderSeeder::class);
        // $this->call(ProductSeeder::class);
        // $this->call(VarianSeeder::class);
        // $this->call(FotoSeeder::class);
        // $this->call(TransaksiSeeder::class);
        // $this->call(HistoryProductSeeder::class);
    }
}
