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
        Schema::create('piutang_produk_piutangs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('piutang_id');
            $table->string('total',255)->nullable();
            $table->foreign('piutang_id')->references('id')->on('piutangs')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('piutang_produk_piutangs');
    }
};
