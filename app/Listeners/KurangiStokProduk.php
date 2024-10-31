<?php

namespace App\Listeners;

use App\Events\TransaksiSelesai;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Transaksi;
use App\Models\Product;

class KurangiStokProduk
{
    /**
     * Create the event listener.
     */
        use InteractsWithQueue;

    public function __construct()
    {
        
    }

    /**
     * Handle the event.
     */
    public function handle(TransaksiSelesai $event): void
    {
        $transaksi = Transaksi::findOrFail($event->transaksiId);
    //    dd($transaksi) ;
        // Log or dd statements for debugging
        // \Log::info('Transaksi ID: ' . $transaksi->id);
        // \Log::info('Product ID: ' . $transaksi->product_id);
        // \Log::info('Jumlah: ' . $transaksi->jumlah);

        // Kurangi stok produk
        $produk = Product::findOrFail($transaksi->product_id);
        $produk->stok -= $transaksi->jumlah;
        $produk->save();

        // \Log::info('Stock Reduced: ' . $produk->stok);
    }

}
