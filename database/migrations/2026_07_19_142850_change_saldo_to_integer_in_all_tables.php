<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Bulatkan semua saldo terlebih dahulu agar tidak ada nilai desimal yang menyebabkan error saat ubah tipe data
        DB::statement('UPDATE kendaraans SET saldo = ROUND(saldo)');
        DB::statement('UPDATE personels SET saldo = ROUND(saldo)');
        DB::statement('UPDATE admin_bbm_stocks SET saldo = ROUND(saldo)');

        Schema::table('kendaraans', function (Blueprint $table) {
            $table->integer('saldo')->default(0)->change();
        });

        Schema::table('personels', function (Blueprint $table) {
            $table->integer('saldo')->default(0)->change();
        });

        Schema::table('admin_bbm_stocks', function (Blueprint $table) {
            $table->integer('saldo')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kendaraans', function (Blueprint $table) {
            $table->decimal('saldo', 15, 2)->default(0)->change();
        });

        Schema::table('personels', function (Blueprint $table) {
            $table->decimal('saldo', 15, 2)->default(0)->change();
        });

        Schema::table('admin_bbm_stocks', function (Blueprint $table) {
            $table->double('saldo')->default(0)->change();
        });
    }
};
