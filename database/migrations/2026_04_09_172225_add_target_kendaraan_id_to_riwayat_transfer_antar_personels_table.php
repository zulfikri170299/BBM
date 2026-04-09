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
        Schema::table('riwayat_transfer_antar_personels', function (Blueprint $table) {
            $table->foreignId('receiver_id')->nullable()->change();
            $table->foreignId('target_kendaraan_id')->nullable()->after('receiver_id')->constrained('kendaraans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('riwayat_transfer_antar_personels', function (Blueprint $table) {
            $table->dropForeign(['target_kendaraan_id']);
            $table->dropColumn('target_kendaraan_id');
            $table->foreignId('receiver_id')->nullable(false)->change();
        });
    }
};
