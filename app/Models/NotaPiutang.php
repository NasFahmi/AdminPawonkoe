<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaPiutang extends Model
{
    use HasFactory;
    protected $fillable =[
        'piutang_id',
        'foto'
    ];
    public function piutangs()
    {
        return $this->belongsTo(Piutang::class);
    }
}
