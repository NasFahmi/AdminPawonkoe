<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryProduct extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'nama_product',
        'harga',
        'deskripsi',
        'link_shopee',
        'stok',
        'spesifikasi_product',
    ];

    public function history_product_transaksi()
    {
        return $this->hasOne(HistoryProductTransaksi::class);
    }
}
