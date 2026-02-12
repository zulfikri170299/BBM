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
        Schema::create('transaksi_bbms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kendaraan_id')->constrained('kendaraans')->onDelete('cascade');
            $table->foreignId('personel_id')->nullable()->constrained('personels')->onDelete('set null');
            $table->foreignId('petugas_id')->constrained('users');
            $table->dateTime('tanggal');
            $table->decimal('liter', 8, 2);
            $table->decimal('harga_per_liter', 10, 2);
            $table->decimal('total', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_bbms');
    }
};
