<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama_product',
        'harga',
        'deskripsi',
        'link_shopee',
        'stok',
        'spesifikasi_product',
        'tersedia',
        'slug'
    ];
    public function fotos()
    {
        return $this->hasMany(Foto::class);
    }

    public function varians()
    {
        return $this->hasMany(Varian::class);
    }

    public function transaksis()
    {
        return $this->hasOne(Transaksi::class);
    }
    public function scopeSearch($query, $search)
    {
        if ($search) {
            $query->where('nama_product', 'like', '%' . $search . '%')
                ->orWhere('deskripsi', 'like', '%' . $search . '%');
        }
    }
}
