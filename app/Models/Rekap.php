<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rekap extends Model
{
    use HasFactory;
    protected $table = 'rekap_keuangan';

    protected $fillable = [
        'tanggal_transaksi',
        'sumber',
        'jumlah',
        'keterangan',
        'id_tabel_asal',
        'tipe_transaksi',
    ];

    public $timestamps = true;
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'id_tabel_asal', 'id');
    }


}
