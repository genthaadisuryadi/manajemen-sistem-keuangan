<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id('transaksi_id');

            // relasi ke mahasiswa
            $table->string('nomor_induk');

            // data transaksi
            $table->date('transaksi_tanggal');
            $table->string('transaksi_jenis', 50)->default('Pemasukan');

            // relasi ke kategori
            $table->unsignedBigInteger('transaksi_kategori');

            // nominal
            $table->decimal('transaksi_nominal', 15, 2)->default(0);
            $table->decimal('total_nominal', 15, 2)->default(0);

            // keterangan & bank
            $table->string('transaksi_keterangan')->nullable();
            $table->unsignedBigInteger('transaksi_bank')->default(1);

            $table->timestamps();

            // ===== OPTIONAL FK (aktifkan kalau tabelnya ada) =====
            // $table->foreign('nomor_induk')->references('nomor_induk')->on('mahasiswa');
            // $table->foreign('transaksi_kategori')->references('kategori_id')->on('kategori');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
