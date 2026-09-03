<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rendis_bbms', function (Blueprint $table) {
            // Hapus kolom bulan, ganti dengan triwulan
            $table->dropColumn('bulan');
            $table->string('triwulan')->after('satker_id'); // TW I, TW II, TW III, TW IV
            
            // Hapus kolom hari single, ganti per-bulan
            $table->dropColumn(['jumlah_hari_operasional', 'jumlah_hari_staff', 'jumlah_hari_pimpinan']);
            
            // Hari per bulan per kategori (3 bulan x 3 kategori = 9 kolom)
            $table->integer('bulan1_hari_operasional')->default(0)->after('tahun');
            $table->integer('bulan1_hari_staff')->default(0)->after('bulan1_hari_operasional');
            $table->integer('bulan1_hari_pimpinan')->default(0)->after('bulan1_hari_staff');
            $table->integer('bulan2_hari_operasional')->default(0)->after('bulan1_hari_pimpinan');
            $table->integer('bulan2_hari_staff')->default(0)->after('bulan2_hari_operasional');
            $table->integer('bulan2_hari_pimpinan')->default(0)->after('bulan2_hari_staff');
            $table->integer('bulan3_hari_operasional')->default(0)->after('bulan2_hari_pimpinan');
            $table->integer('bulan3_hari_staff')->default(0)->after('bulan3_hari_operasional');
            $table->integer('bulan3_hari_pimpinan')->default(0)->after('bulan3_hari_staff');

            // Susut 1.5%
            $table->decimal('susut_persen', 5, 2)->default(1.5)->after('jumlah_pembelian');

            // Kolom nullable satker_id untuk super_admin yang buat rendis global
            $table->foreignId('satker_id')->nullable()->change();
        });

        Schema::table('rendis_kendaraans', function (Blueprint $table) {
            // Hapus kolom lama
            $table->dropColumn(['jumlah_liter', 'koef', 'jumlah_hari', 'total_liter']);
            
            // Data per bulan (3 bulan)
            $table->decimal('liter_per_hari', 10, 2)->default(0)->after('kendaraan_id');
            $table->decimal('bulan1_total', 10, 2)->default(0)->after('liter_per_hari');
            $table->decimal('bulan2_total', 10, 2)->default(0)->after('bulan1_total');
            $table->decimal('bulan3_total', 10, 2)->default(0)->after('bulan2_total');
            $table->decimal('total_liter', 10, 2)->default(0)->after('bulan3_total');
            
            // Kolom jenis BBM (pertamax atau pertamina_dex)
            $table->string('jenis_bbm')->default('pertamax')->after('total_liter');
        });
    }

    public function down(): void
    {
        Schema::table('rendis_bbms', function (Blueprint $table) {
            $table->dropColumn([
                'triwulan',
                'bulan1_hari_operasional', 'bulan1_hari_staff', 'bulan1_hari_pimpinan',
                'bulan2_hari_operasional', 'bulan2_hari_staff', 'bulan2_hari_pimpinan',
                'bulan3_hari_operasional', 'bulan3_hari_staff', 'bulan3_hari_pimpinan',
                'susut_persen',
            ]);
            $table->string('bulan');
            $table->integer('jumlah_hari_operasional')->default(0);
            $table->integer('jumlah_hari_staff')->default(0);
            $table->integer('jumlah_hari_pimpinan')->default(0);
        });

        Schema::table('rendis_kendaraans', function (Blueprint $table) {
            $table->dropColumn(['liter_per_hari', 'bulan1_total', 'bulan2_total', 'bulan3_total', 'total_liter', 'jenis_bbm']);
            $table->integer('jumlah_liter')->default(0);
            $table->integer('koef')->nullable();
            $table->integer('jumlah_hari')->nullable();
            $table->integer('total_liter')->nullable();
        });
    }
};
