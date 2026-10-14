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
            $table->string('status_approval')->default('disetujui')->index();
            $table->string('aksi_approval')->nullable(); // 'tambah', 'edit', 'import'
            $table->text('alasan_penolakan')->nullable();
            $table->text('pending_changes')->nullable();
            $table->unsignedBigInteger('diajukan_oleh')->nullable();
            $table->unsignedBigInteger('disetujui_oleh')->nullable();
            $table->timestamp('disetujui_pada')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kendaraans', function (Blueprint $table) {
            $table->dropColumn([
                'status_approval',
                'aksi_approval',
                'alasan_penolakan',
                'pending_changes',
                'diajukan_oleh',
                'disetujui_oleh',
                'disetujui_pada'
            ]);
        });
    }
};
