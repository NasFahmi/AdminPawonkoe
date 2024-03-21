<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preorder extends Model
{
    use HasFactory;
    protected $fillable = [
        'is_DP',
        'down_payment',
        'tanggal_pembayaran_down_payment',
    ];
    public function transaksis(){
        return $this->hasOne(Transaksi::class);
    }
    public function scopeSearch($query, $search)
    {
        if ($search) {
            $query->whereHas('products', function ($query) use ($search) {
                $query->where('nama_product', 'like', '%' . $search . '%');
            })
                ->orWhereHas('pembelis', function ($query) use ($search) {
                    $query->where('nama', 'like', '%' . $search . '%');
                });
        }

    }
}
