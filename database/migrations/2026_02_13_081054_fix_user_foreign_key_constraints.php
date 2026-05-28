<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            // Check if our previous attempt left an old table
            Schema::dropIfExists('transaksi_bbms_old');
            Schema::dropIfExists('personels_old');

            // 1. Rename old table
            Schema::rename('transaksi_bbms', 'transaksi_bbms_old');
            
            // 2. Create new table with correct foreign key definition
            Schema::create('transaksi_bbms', function (Blueprint $table) {
                $table->id();
                $table->foreignId('kendaraan_id')->nullable()->constrained('kendaraans')->onDelete('cascade');
                $table->foreignId('personel_id')->nullable()->constrained('personels')->onDelete('set null');
                $table->foreignId('petugas_id')->nullable()->constrained('users')->onDelete('set null');
                $table->dateTime('tanggal');
                $table->decimal('liter', 8, 2);
                $table->decimal('harga_per_liter', 10, 2);
                $table->decimal('total', 15, 2);
                $table->timestamps();
            });
            
            // 3. Copy data
            DB::statement('INSERT INTO transaksi_bbms (id, kendaraan_id, personel_id, petugas_id, tanggal, liter, harga_per_liter, total, created_at, updated_at) 
                           SELECT id, kendaraan_id, personel_id, petugas_id, tanggal, liter, harga_per_liter, total, created_at, updated_at FROM transaksi_bbms_old');
            
            // 4. Drop old table
            Schema::dropIfExists('transaksi_bbms_old');
        } else {
            Schema::table('transaksi_bbms', function (Blueprint $table) {
                $table->unsignedBigInteger('petugas_id')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ...
    }
};
