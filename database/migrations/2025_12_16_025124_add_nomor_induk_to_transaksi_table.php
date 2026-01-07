<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
{
    if (
        Schema::hasTable('transaksi') &&
        !Schema::hasColumn('transaksi', 'nomor_induk')
    ) {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->string('nomor_induk')->after('transaksi_id');
        });
    }
}

public function down(): void
{
    if (
        Schema::hasTable('transaksi') &&
        Schema::hasColumn('transaksi', 'nomor_induk')
    ) {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn('nomor_induk');
        });
    }
}

};
