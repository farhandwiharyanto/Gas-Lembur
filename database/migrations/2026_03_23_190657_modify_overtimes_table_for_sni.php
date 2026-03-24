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
        Schema::table('overtimes', function (Blueprint $table) {
            $table->renameColumn('nama', 'employee_name');
            $table->renameColumn('nik', 'employee_no');
            $table->string('sub_bagian')->nullable()->after('bagian');
            $table->string('divisi')->nullable()->after('sub_bagian');
            $table->string('direktorat')->nullable()->after('divisi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('overtimes', function (Blueprint $table) {
            $table->renameColumn('employee_name', 'nama');
            $table->renameColumn('employee_no', 'nik');
            $table->dropColumn(['sub_bagian', 'divisi', 'direktorat']);
        });
    }
};
