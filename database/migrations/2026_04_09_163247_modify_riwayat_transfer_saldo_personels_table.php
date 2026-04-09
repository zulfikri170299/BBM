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
        Schema::table('riwayat_transfer_saldo_personels', function (Blueprint $table) {
            $table->foreignId('personel_id')->nullable()->change();
            $table->foreignId('tujuan_kendaraan_id')->nullable()->after('personel_id')->constrained('kendaraans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('riwayat_transfer_saldo_personels', function (Blueprint $table) {
            $table->foreignId('personel_id')->nullable(false)->change();
            $table->dropColumn('tujuan_kendaraan_id');
        });
    }
};
