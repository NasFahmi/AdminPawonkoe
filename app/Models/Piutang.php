<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Piutang extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'nominal',
        'is_complete',
        'catatan',
        'tanggal_disetorkan',
        'bukti_nota',
    ];
}
