<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukPiutang extends Model
{
    use HasFactory;
    protected $fillable = [
        'produk_piutang_id',
        'nama_produk',
        'jumlah',
        'harga',
        'total',
    ];
    public function piutang_produk_piutangs()
    {
        return $this->belongsTo(Piutang::class,'id','produk_piutang_id');
    }

}
