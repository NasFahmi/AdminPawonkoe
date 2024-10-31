<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('nama_product', 100);
            $table->longText('slug');
            $table->string('harga', 100);
            $table->longtext('deskripsi');
            $table->string('link_shopee', 255);
            $table->string('stok', 100);
            $table->longText('spesifikasi_product');
            $table->boolean('tersedia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
