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
        Schema::table('transaksi_bbms', function (Blueprint $table) {
            $table->string('jenis_bbm')->nullable()->after('petugas_id');
        });

        // Backfill data lama agar laporan tidak kosong
        \DB::table('transaksi_bbms')->whereNull('jenis_bbm')->get()->each(function ($t) {
            $bbm = null;
            if ($t->kendaraan_id) {
                $bbm = \DB::table('kendaraans')->where('id', $t->kendaraan_id)->value('jenis_bbm');
            } elseif ($t->personel_id) {
                $bbm = \DB::table('personels')->where('id', $t->personel_id)->value('jenis_bbm');
            }

            if ($bbm) {
                \DB::table('transaksi_bbms')->where('id', $t->id)->update(['jenis_bbm' => $bbm]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_bbms', function (Blueprint $table) {
            $table->dropColumn('jenis_bbm');
        });
    }
};
