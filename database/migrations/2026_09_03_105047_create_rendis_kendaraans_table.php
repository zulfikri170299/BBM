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
        Schema::create('rendis_kendaraans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rendis_bbm_id')->constrained()->cascadeOnDelete();
            $table->foreignId('kendaraan_id')->constrained()->cascadeOnDelete();
            $table->integer('jumlah_liter')->default(0);
            $table->integer('koef')->nullable();
            $table->integer('jumlah_hari')->nullable();
            $table->integer('total_liter')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rendis_kendaraans');
    }
};
