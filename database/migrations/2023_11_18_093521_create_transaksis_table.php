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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->unsignedBigInteger('pembeli_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable(); // Allow NULL
            $table->unsignedBigInteger('methode_pembayaran_id');
            $table->bigInteger('jumlah');
            $table->bigInteger('total_harga');
            $table->string('keterangan', 255)->nullable();
            $table->boolean('is_Preorder')->nullable();
            $table->unsignedBigInteger('Preorder_id')->nullable();
            $table->boolean('is_complete');
            $table->timestamps();

            // relasi
            $table->foreign('pembeli_id')->references('id')->on('pembelis')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('methode_pembayaran_id')->references('id')->on('methode_pembayarans')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('Preorder_id')->references('id')->on('preorders')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
