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
        Schema::table('trips', function (Blueprint $table) {
            //
            $table->dateTime('actual_departure_time')->nullable()->after('arrival_time');
            $table->dateTime('actual_arrival_time')->nullable()->after('actual_departure_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            //
            $table->dropColumn('actual_departure_time');
            $table->dropColumn('actual_arrival_time');
        });
    }
};
