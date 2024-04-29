<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Piutang extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama_toko',
        'penghasilan',
        'sewa_titip',
        'catatan',
        'is_complete',
        'tanggal_disetorkan',
        'tanggal_lunas',
        'bukti_nota',
    ];
    use SoftDeletes;
    public function notas()
    {
        return $this->hasMany(NotaPiutang::class);
    }
    public function piutang_produk_piutangs()
    {
        return $this->belongsTo(PiutangProdukPiutang::class, 'piutang_id', 'id');
    }
}
