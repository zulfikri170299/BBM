<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('admin_bbm_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_bbm')->unique();
            $table->double('saldo')->default(0);
            $table->timestamps();
        });

        // Insert default fuel types
        $fuelTypes = ['Pertamax', 'Pertamina Dex'];
        foreach ($fuelTypes as $type) {
            \Illuminate\Support\Facades\DB::table('admin_bbm_stocks')->insert([
                'jenis_bbm' => $type,
                'saldo' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('admin_bbm_stocks');
    }
};
