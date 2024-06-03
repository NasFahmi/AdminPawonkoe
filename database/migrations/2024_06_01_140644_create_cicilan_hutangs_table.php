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
        Schema::create('cicilan_hutangs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hutangId');
            $table->bigInteger('nominal');
            $table->timestamps();

            $table->foreign('hutangId')->references('id')->on('hutangs')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cicilan_hutangs');
    }
};
