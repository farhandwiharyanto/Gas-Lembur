<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('overtimes', function (Blueprint $table) {
            $table->string('nama_lemburan')->nullable();
            $table->time('jam_masuk')->nullable();
            $table->time('jam_keluar')->nullable();
            $table->string('total_jam')->nullable();
            $table->string('nomor_tiket')->nullable();
            $table->string('pemberi_lembur')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('overtimes', function (Blueprint $table) {
            $table->dropColumn([
                'nama_lemburan', 
                'jam_masuk', 
                'jam_keluar', 
                'total_jam', 
                'nomor_tiket', 
                'pemberi_lembur'
            ]);
        });
    }
};
