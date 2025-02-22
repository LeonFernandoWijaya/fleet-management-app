<?php

namespace Database\Seeders;

use App\Models\TripStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TripStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        TripStatus::create([
            'name' => 'Scheduled',
        ]);

        TripStatus::create([
            'name' => 'In Progress',
        ]);

        TripStatus::create([
            'name' => 'Delayed',
        ]);

        TripStatus::create([
            'name' => 'Completed',
        ]);
    }
}
