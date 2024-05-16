<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisModal extends Model
{
    use HasFactory;
    public function modal()
    {
        return $this->belongsTo(Modal::class, 'id', 'jenis_modal_id');
    }
}
