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
        Schema::table('riwayat_topups', function (Blueprint $table) {
            $table->unsignedBigInteger('kendaraan_id')->nullable()->change();
            $table->foreignId('personel_id')->nullable()->after('kendaraan_id')->constrained('personels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('riwayat_topups', function (Blueprint $table) {
            $table->dropForeign(['personel_id']);
            $table->dropColumn('personel_id');
            $table->unsignedBigInteger('kendaraan_id')->nullable(false)->change();
        });
    }
};
