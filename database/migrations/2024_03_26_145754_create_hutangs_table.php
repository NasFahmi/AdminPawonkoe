<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hutangs', function (Blueprint $table) {
            $table->id();
            $table->string('nama',255);
            $table->string('nominal',255);
            $table->longText('catatan')->nullable();
            $table->boolean('is_complete')->default(0);
            $table->timestamp('tanggal_hutang')->nullable();
            $table->timestamp('tenggat_pembayaran')->nullable();
            $table->timestamp('tanggal_pembayaran')->nullable();
            $table->string('bukti_pembayaran',255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hutangs');
    }
};
