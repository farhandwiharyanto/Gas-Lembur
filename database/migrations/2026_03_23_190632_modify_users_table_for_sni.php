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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->nullable()->after('id');
            $table->string('sub_bagian')->nullable()->after('bagian');
            $table->string('divisi')->nullable()->after('sub_bagian');
            $table->string('direktorat')->nullable()->after('divisi');
            // employee_no will map to existing `nik` or we can rename it. 
            // We'll keep `nik` and map `employee_no` to it, and map `employee_name` to `name`.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'sub_bagian', 'divisi', 'direktorat']);
        });
    }
};
