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
        Schema::create('daily_meter_readings', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('jenis_bbm');
            $table->decimal('meter_awal', 15, 2)->default(0);
            $table->decimal('meter_akhir', 15, 2)->default(0);
            $table->timestamps();

            $table->unique(['tanggal', 'jenis_bbm']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_meter_readings');
    }
};
