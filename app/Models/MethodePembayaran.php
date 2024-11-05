<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MethodePembayaran extends Model
{
    use HasFactory;
    // Matikan penggunaan timestamps
    public $timestamps = false;
    protected $fillable = [
        'methode_pembayaran'
    ];
    public function transaksis()
    {
        return $this->hasOne(Transaksi::class);
    }
}
