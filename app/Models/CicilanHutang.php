<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CicilanHutang extends Model
{
    use HasFactory;
    protected $fillable = [
        'hutangId',
        'nominal',
    ];



    public function cicilan_hutang()
    {
        return $this->belongsTo(Hutang::class, 'hutangId', 'id');
    }
}
