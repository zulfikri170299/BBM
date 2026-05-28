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
        Schema::table('personels', function (Blueprint $table) {
            $table->string('jenis_bbm')->nullable()->after('saldo');
            $table->string('pin', 6)->default('123456')->after('jenis_bbm');
            $table->string('barcode')->nullable()->unique()->after('pin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personels', function (Blueprint $table) {
            $table->dropColumn(['jenis_bbm', 'pin', 'barcode']);
        });
    }
};
