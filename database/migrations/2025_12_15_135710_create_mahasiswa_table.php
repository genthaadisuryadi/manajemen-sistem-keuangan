<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->id('mahasiswa_id');

            // WAJIB UNIQUE untuk FK
            $table->string('nomor_induk')->unique();

            $table->string('mahasiswa');
            $table->unsignedBigInteger('kategori_id')->nullable();

            $table->foreign('kategori_id')
                  ->references('kategori_id')
                  ->on('kategori')
                  ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};
