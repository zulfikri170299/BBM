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
        Schema::table('penanda_tangans', function (Blueprint $table) {
            $table->string('jabatan2')->nullable()->after('jabatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penanda_tangans', function (Blueprint $table) {
            $table->dropColumn('jabatan2');
        });
    }
};
