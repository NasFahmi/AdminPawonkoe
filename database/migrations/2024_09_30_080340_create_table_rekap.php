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
        Schema::create('rekap_keuangan', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_transaksi'); // Tanggal transaksi uang keluar
            $table->string('sumber'); // Kategori atau jenis pengeluaran (Hutang, Beban Operasional, dll)
            $table->decimal('jumlah', 15, 2); // Nominal uang keluar (format desimal dengan 15 digit total dan 2 digit desimal)
            $table->text('keterangan')->nullable(); // Keterangan tambahan (deskripsi transaksi)
            $table->unsignedBigInteger(column: 'id_tabel_asal'); // ID dari tabel asal terkait transaksi
            $table->enum('tipe_transaksi', allowed: ['keluar', 'masuk']); // Menentukan apakah ini transaksi keluar atau masuk
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Rekap-keuangan');
    }
};
