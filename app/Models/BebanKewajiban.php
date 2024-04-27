<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BebanKewajiban extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'jenis',
        'nama',
        'nominal',
        'tanggal',
    ];

    public $timestamps = false;
}
