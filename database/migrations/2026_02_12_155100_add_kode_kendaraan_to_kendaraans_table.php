<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kendaraans', function (Blueprint $table) {
            $table->string('kode_kendaraan')->nullable()->after('satker_id');
        });

        // Generate kode untuk kendaraan existing
        $kendaraans = \App\Models\Kendaraan::all();
        foreach ($kendaraans as $kendaraan) {
            $kendaraan->kode_kendaraan = 'KND-' . str_pad($kendaraan->id, 5, '0', STR_PAD_LEFT);
            $kendaraan->save();
        }
    }

    public function down(): void
    {
        Schema::table('kendaraans', function (Blueprint $table) {
            $table->dropColumn('kode_kendaraan');
        });
    }
};
