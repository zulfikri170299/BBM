<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rendis_kendaraans', function (Blueprint $table) {
            $table->decimal('liter_per_hari_b2', 8, 2)->default(0)->after('liter_per_hari');
            $table->decimal('liter_per_hari_b3', 8, 2)->default(0)->after('liter_per_hari_b2');
        });
    }

    public function down(): void
    {
        Schema::table('rendis_kendaraans', function (Blueprint $table) {
            $table->dropColumn(['liter_per_hari_b2', 'liter_per_hari_b3']);
        });
    }
};
