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
        if (!Schema::hasColumn('transaksi_bbms', 'nopol')) {
            Schema::table('transaksi_bbms', function (Blueprint $table) {
                $table->string('nopol')->nullable()->after('kendaraan_id');
            });
        }

        // Isi nopol transaksi yang sudah ada dari tabel kendaraans
        try {
            \DB::statement("UPDATE transaksi_bbms SET nopol = (SELECT no_polisi FROM kendaraans WHERE kendaraans.id = transaksi_bbms.kendaraan_id) WHERE kendaraan_id IS NOT NULL AND (nopol IS NULL OR nopol = '')");
        } catch (\Throwable $e) {
            // Abaikan jika tidak ada data
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('transaksi_bbms', 'nopol')) {
            Schema::table('transaksi_bbms', function (Blueprint $table) {
                $table->dropColumn('nopol');
            });
        }
    }
};
