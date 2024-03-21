<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Pembeli;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $fillable = [
        'tanggal',
        'pembeli_id',
        'product_id',
        'methode_pembayaran_id',
        'jumlah',
        'total_harga',
        'keterangan',
        'is_Preorder',
        'Preorder_id',
        'is_complete',
    ];
    public function pembelis()
    {
        return $this->belongsTo(Pembeli::class, 'pembeli_id', 'id');
    }
    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    public function methode_pembayaran()
    {
        return $this->belongsTo(MethodePembayaran::class, 'methode_pembayaran_id', 'id');
    }
    public function preorders()
    {
        return $this->belongsTo(Preorder::class, 'Preorder_id', 'id');
    }
    public function history_product_transaksis()
    {
        return $this->hasMany(HistoryProductTransaksi::class, 'transaksi_id', 'id');
    }
    // Transaksi.php
    public function scopeSearch($query, $search)
    {
        if ($search) {
            $query->whereHas('products', function ($query) use ($search) {
                $query->where('nama_product', 'like', '%' . $search . '%');
            })
                ->orWhere('tanggal', 'like', '%' . $search . '%');
        }
    }
}
