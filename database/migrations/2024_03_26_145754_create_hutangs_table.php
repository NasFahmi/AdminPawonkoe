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
            $table->string('nama', 255);
            $table->longText('catatan')->nullable();
            $table->boolean('status')->default(0);
            $table->bigInteger('jumlah_hutang');
            $table->timestamp('tenggat_waktu')->nullable();
            $table->timestamp('tanggal_lunas')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
