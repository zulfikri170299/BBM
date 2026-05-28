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
        Schema::create('hutangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('satker_id')->constrained('satkers')->onDelete('cascade');
            $table->foreignId('petugas_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('jenis_kendaraan');
            $table->string('nopol');
            $table->string('jenis_bbm');
            $table->decimal('jumlah_bon', 15, 2);
            $table->enum('status', ['belum_dibayar', 'sudah_dibayar'])->default('belum_dibayar');
            $table->foreignId('admin_bayar_id')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('tanggal_bayar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hutangs');
    }
};
