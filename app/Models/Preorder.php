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
}
