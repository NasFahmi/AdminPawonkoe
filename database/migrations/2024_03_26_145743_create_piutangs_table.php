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
        Schema::create('piutangs', function (Blueprint $table) {
            $table->id();
            $table->string('nama',255);
            $table->string('nominal',255);
            $table->longText('catatan')->nullable();
            $table->boolean('is_complete')->default(0);
            $table->timestamps('tanggal_disetorkan');
            $table->string('bukti_nota',255);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('piutangs');
    }
};
