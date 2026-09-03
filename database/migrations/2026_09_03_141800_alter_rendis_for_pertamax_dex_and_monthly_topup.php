<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rendis_bbms', function (Blueprint $table) {
            $table->dropColumn('jumlah_pembelian');
            $table->dropColumn('is_topup_executed');

            $table->integer('pembelian_pertamax')->default(0)->after('tahun');
            $table->integer('pembelian_pertamina_dex')->default(0)->after('pembelian_pertamax');
            
            $table->boolean('is_topup_b1')->default(false)->after('susut_persen');
            $table->boolean('is_topup_b2')->default(false)->after('is_topup_b1');
            $table->boolean('is_topup_b3')->default(false)->after('is_topup_b2');
        });
    }

    public function down(): void
    {
        Schema::table('rendis_bbms', function (Blueprint $table) {
            $table->integer('jumlah_pembelian')->default(0);
            $table->boolean('is_topup_executed')->default(false);
            
            $table->dropColumn(['pembelian_pertamax', 'pembelian_pertamina_dex', 'is_topup_b1', 'is_topup_b2', 'is_topup_b3']);
        });
    }
};
