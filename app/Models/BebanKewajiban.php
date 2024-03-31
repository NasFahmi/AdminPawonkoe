<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BebanKewajiban extends Model
{
    use HasFactory;

    protected $fillable = [
        'jenis',
        'nama',
        'nominal',
        'tanggal',
    ];
}
