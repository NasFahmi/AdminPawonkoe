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
        Schema::create('modals', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nominal');
            $table->string('penyedia');
            $table->bigInteger('jumlah');
            $table->timestamps('tanggal');
            $table->unsignedBigInteger('jenis_modal_id');
            $table->foreign('jenis_modal_id')->references('id')->on('jenis_modals')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modals');
    }
};
