<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Modal extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'jenis',
        'nama',
        'nominal',
        'penyedia',
        'jumlah',
        'tanggal',
        'jenis_modal_id',
    ];
    public $timestamps = false;
    public function jenis_modal()
    {
        return $this->belongsTo(JenisModal::class, 'jenis_modal_id', 'id');
    }
};