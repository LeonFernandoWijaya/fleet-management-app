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
            $table->string('departure_latitude')->nullable()->after('trip_issue');
            $table->string('departure_longitude')->nullable()->after('departure_latitude');
            $table->string('arrival_latitude')->nullable()->after('departure_longitude');
            $table->string('arrival_longitude')->nullable()->after('arrival_latitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            //
            $table->dropColumn('departure_latitude');
            $table->dropColumn('departure_longitude');
            $table->dropColumn('arrival_latitude');
            $table->dropColumn('arrival_longitude');
        });
    }
};
