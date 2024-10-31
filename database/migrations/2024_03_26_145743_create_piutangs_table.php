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
            $table->string('nama_toko',255);
            $table->string('penghasilan',255)->nullable();
            $table->string('sewa_titip',255);
            $table->longText('catatan')->nullable();
            $table->boolean('is_complete')->default(0);
            $table->timestamp('tanggal_disetorkan')->nullable();
            $table->timestamp('tanggal_lunas')->nullable();
            $table->softDeletes();
            $table->timestamps();
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
