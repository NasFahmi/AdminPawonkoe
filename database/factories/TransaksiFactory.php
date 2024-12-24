<?php

namespace Database\Factories;

use App\Models\MethodePembayaran;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaksi>
 */
class TransaksiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Mendapatkan product random
        $product = Product::inRandomOrder()->first();

        // Jika tidak ada produk, factory gagal
        if (!$product) {
            return [];
        }

        $jumlah = $this->faker->numberBetween(1, $product->stok);
        $totalHarga = $jumlah * $product->harga;

        return [
            'tanggal' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'product_id' => $product->id,
            'methode_pembayaran_id' => rand(1, 4),
            'jumlah' => $jumlah,
            'total_harga' => $totalHarga,
            'keterangan' => $this->faker->sentence(6),
            'is_Preorder' => false,
            'Preorder_id' => null,
            'is_complete' => true,
        ];
    }
}
