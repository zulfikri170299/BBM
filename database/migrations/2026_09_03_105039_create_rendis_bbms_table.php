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
        Schema::create('rendis_bbms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('satker_id')->constrained()->cascadeOnDelete();
            $table->string('bulan');
            $table->string('tahun');
            $table->integer('jumlah_hari_operasional')->default(0);
            $table->integer('jumlah_hari_staff')->default(0);
            $table->integer('jumlah_hari_pimpinan')->default(0);
            $table->integer('jumlah_pembelian')->default(0);
            $table->boolean('is_topup_executed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rendis_bbms');
    }
};
