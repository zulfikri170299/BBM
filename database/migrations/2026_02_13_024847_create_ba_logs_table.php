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
        Schema::dropIfExists('ba_logs');
        Schema::create('ba_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('satker_id')->constrained('satkers')->onDelete('cascade');
            $table->integer('bulan');
            $table->integer('tahun');
            $table->decimal('total_pertamax', 15, 2)->default(0);
            $table->decimal('total_dex', 15, 2)->default(0);
            $table->string('file_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ba_logs');
    }
};
