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
        Schema::table('kendaraans', function (Blueprint $table) {
            $table->boolean('bebas_bon')->default(false)->index();
            $table->text('alasan_bebas_bon')->nullable();
            $table->timestamp('bebas_bon_updated_at')->nullable();
            $table->unsignedBigInteger('bebas_bon_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kendaraans', function (Blueprint $table) {
            $table->dropIndex(['bebas_bon']);
            $table->dropColumn(['bebas_bon', 'alasan_bebas_bon', 'bebas_bon_updated_at', 'bebas_bon_by']);
        });
    }
};
