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
        if (!Schema::hasColumn('personels', 'pin')) {
            Schema::table('personels', function (Blueprint $table) {
                $table->string('pin')->nullable()->default('123456')->after('nrp');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personels', function (Blueprint $table) {
            $table->dropColumn('pin');
        });
    }
};
