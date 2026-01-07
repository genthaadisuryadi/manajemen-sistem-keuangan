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
        !Schema::hasColumn('transaksi', 'total_nominal')
    ) {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->decimal('total_nominal', 15, 2)
                  ->default(0)
                  ->after('transaksi_nominal');
        });
    }
}

public function down(): void
{
    if (
        Schema::hasTable('transaksi') &&
        Schema::hasColumn('transaksi', 'total_nominal')
    ) {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn('total_nominal');
        });
    }
}
};
