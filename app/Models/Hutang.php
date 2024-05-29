<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hutang extends Model
{
    use HasFactory, SoftDeletes;
    // $table->string('nama',255);
    // $table->string('nominal',255);
    // $table->longText('catatan')->nullable();
    // $table->boolean('is_complete')->default(0);
    // $table->timestamp('tanggal_hutang')->nullable();
    // $table->timestamp('tenggat_pembayaran')->nullable();
    // $table->timestamp('tanggal_pembayaran')->nullable();
    protected $fillable = [
        'nama',
        'nominal',
        'catatan',
        'is_complete',
        'tanggal_hutang',
        'tenggat_pembayaran',
        'tanggal_pembayaran',
    ];
}
