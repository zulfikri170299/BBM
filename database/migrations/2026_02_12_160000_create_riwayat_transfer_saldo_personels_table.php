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
        Schema::create('riwayat_transfer_saldo_personels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('satker_id')->constrained('satkers')->onDelete('cascade');
            $table->foreignId('kendaraan_id')->constrained('kendaraans')->onDelete('cascade');
            $table->foreignId('personel_id')->constrained('personels')->onDelete('cascade');
            $table->decimal('jumlah', 15, 2);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_transfer_saldo_personels');
    }
};
