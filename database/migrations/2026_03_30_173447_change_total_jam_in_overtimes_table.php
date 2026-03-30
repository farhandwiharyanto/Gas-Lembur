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
            // PostgreSQL requires explicit casting when changing types from varchar to numeric
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE overtimes ALTER COLUMN total_jam TYPE DECIMAL(8,2) USING total_jam::decimal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('overtimes', function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE overtimes ALTER COLUMN total_jam TYPE VARCHAR(255) USING total_jam::varchar');
        });
    }
};
