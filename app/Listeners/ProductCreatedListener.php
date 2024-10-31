<?php

namespace App\Listeners;

use Illuminate\Support\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\HistoryProduct;
use App\Events\ProductCreated;

class ProductCreatedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        
    }

    /**
     * Handle the event.
     */
    public function handle(ProductCreated $event): void
    {
            HistoryProduct::insert([
            'product_id'=>$event->productID,
            'nama_product' => $event->product->nama_product,
            'harga' => $event->product->harga,
            'deskripsi' => $event->product->deskripsi,
            'link_shopee' => $event->product->link_shopee,
            'stok' => $event->product->stok,
            'spesifikasi_product' => $event->product->spesifikasi_product,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
