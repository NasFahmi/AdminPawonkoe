<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produksi extends Model
{
    use HasFactory;
    protected $fillable = [
        'produk',
        'jumlah',
        'volume',
        'tanggal',
    ];
    public $timestamps = false;
}
