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
            $table->index('tanggal', 'idx_trx_tanggal');
            $table->index('satker_id', 'idx_trx_satker_id');
            $table->index('kendaraan_id', 'idx_trx_kendaraan_id');
            $table->index('personel_id', 'idx_trx_personel_id');
            $table->index('jenis_bbm', 'idx_trx_jenis_bbm');
            $table->index('created_at', 'idx_trx_created_at');
        });

        Schema::table('riwayat_topups', function (Blueprint $table) {
            $table->index('tipe', 'idx_topup_tipe');
            $table->index('satker_id', 'idx_topup_satker_id');
            $table->index('kendaraan_id', 'idx_topup_kendaraan_id');
            $table->index('created_at', 'idx_topup_created_at');
        });

        Schema::table('hutangs', function (Blueprint $table) {
            $table->index('satker_id', 'idx_hutang_satker_id');
            $table->index('status', 'idx_hutang_status');
            $table->index('nopol', 'idx_hutang_nopol');
            $table->index('tanggal_bon', 'idx_hutang_tanggal_bon');
        });

        Schema::table('kendaraans', function (Blueprint $table) {
            $table->index('satker_id', 'idx_knd_satker_id');
            $table->index('no_polisi', 'idx_knd_no_polisi');
            $table->index('jenis_bbm', 'idx_knd_jenis_bbm');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('role', 'idx_users_role');
            $table->index('satker_id', 'idx_users_satker_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_bbms', function (Blueprint $table) {
            $table->dropIndex('idx_trx_tanggal');
            $table->dropIndex('idx_trx_satker_id');
            $table->dropIndex('idx_trx_kendaraan_id');
            $table->dropIndex('idx_trx_personel_id');
            $table->dropIndex('idx_trx_jenis_bbm');
            $table->dropIndex('idx_trx_created_at');
        });

        Schema::table('riwayat_topups', function (Blueprint $table) {
            $table->dropIndex('idx_topup_tipe');
            $table->dropIndex('idx_topup_satker_id');
            $table->dropIndex('idx_topup_kendaraan_id');
            $table->dropIndex('idx_topup_created_at');
        });

        Schema::table('hutangs', function (Blueprint $table) {
            $table->dropIndex('idx_hutang_satker_id');
            $table->dropIndex('idx_hutang_status');
            $table->dropIndex('idx_hutang_nopol');
            $table->dropIndex('idx_hutang_tanggal_bon');
        });

        Schema::table('kendaraans', function (Blueprint $table) {
            $table->dropIndex('idx_knd_satker_id');
            $table->dropIndex('idx_knd_no_polisi');
            $table->dropIndex('idx_knd_jenis_bbm');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_role');
            $table->dropIndex('idx_users_satker_id');
        });
    }
};
