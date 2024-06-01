<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hutang extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'nama',
        'catatan',
        'status',
        'jumlah_hutang',
        'tanggal_lunas',
    ];

    public function hutang_cicilan()
    {
        return $this->hasMany(CicilanHutang::class, 'id', 'hutangId');
    }
}
