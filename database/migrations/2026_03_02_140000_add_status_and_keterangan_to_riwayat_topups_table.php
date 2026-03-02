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
            $table->string('status')->nullable()->after('metode');
            $table->text('keterangan')->nullable()->after('status');
        });
        
        // Backfill status success untuk data lama
        \DB::table('riwayat_topups')->whereNull('status')->update(['status' => 'success']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('riwayat_topups', function (Blueprint $table) {
            $table->dropColumn(['status', 'keterangan']);
        });
    }
};
