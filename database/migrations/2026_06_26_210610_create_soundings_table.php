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
        Schema::create('soundings', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('jenis_bbm');
            $table->decimal('stok_awal', 10, 2);
            $table->decimal('stok_akhir', 10, 2);
            $table->decimal('pengeluaran_aplikasi', 10, 2)->default(0);
            $table->decimal('susut', 10, 2)->default(0);
            $table->foreignId('petugas_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('dokumentasi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soundings');
    }
};
