<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryProductTransaksi extends Model
{
    use HasFactory;
    protected $fillable = [
        'transaksi_id',
        'history_product_id'
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id', 'id');
    }

    public function history_product()
    {
        return $this->belongsTo(HistoryProduct::class, 'history_product_id', 'id');
    }
}
