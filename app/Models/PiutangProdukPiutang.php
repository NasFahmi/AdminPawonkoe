<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PiutangProdukPiutang extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'piutang_id',
        'total'
    ];
    public function piutangs(){
        return $this->hasOne(Piutang::class);
    }
    
    public function produk_piutangs()
    {
        return $this->hasMany(ProdukPiutang::class);
    }
}
