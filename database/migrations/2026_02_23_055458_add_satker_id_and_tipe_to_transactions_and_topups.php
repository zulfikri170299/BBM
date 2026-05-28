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
            $table->foreignId('satker_id')->nullable()->after('id')->constrained('satkers')->onDelete('cascade');
        });

        Schema::table('riwayat_topups', function (Blueprint $table) {
            $table->foreignId('satker_id')->nullable()->after('id')->constrained('satkers')->onDelete('cascade');
            $table->enum('tipe', ['masuk', 'keluar'])->default('masuk')->after('jumlah');
        });

        // Backfill existing data
        DB::table('transaksi_bbms')->get()->each(function ($item) {
            $kendaraan = DB::table('kendaraans')->where('id', $item->kendaraan_id)->first();
            if ($kendaraan) {
                DB::table('transaksi_bbms')->where('id', $item->id)->update(['satker_id' => $kendaraan->satker_id]);
            }
        });

        DB::table('riwayat_topups')->get()->each(function ($item) {
            $kendaraan = DB::table('kendaraans')->where('id', $item->kendaraan_id)->first();
            if ($kendaraan) {
                DB::table('riwayat_topups')->where('id', $item->id)->update(['satker_id' => $kendaraan->satker_id]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_bbms', function (Blueprint $table) {
            $table->dropForeign(['satker_id']);
            $table->dropColumn('satker_id');
        });

        Schema::table('riwayat_topups', function (Blueprint $table) {
            $table->dropForeign(['satker_id']);
            $table->dropColumn(['satker_id', 'tipe']);
        });
    }
};
