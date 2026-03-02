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
            $table->string('jenis_bbm')->nullable()->after('user_id');
        });

        // Backfill data lama
        \DB::table('riwayat_topups')->whereNull('jenis_bbm')->get()->each(function ($r) {
            $bbm = \DB::table('kendaraans')->where('id', $r->kendaraan_id)->value('jenis_bbm');
            if ($bbm) {
                \DB::table('riwayat_topups')->where('id', $r->id)->update(['jenis_bbm' => $bbm]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('riwayat_topups', function (Blueprint $table) {
            $table->dropColumn('jenis_bbm');
        });
    }
};
