<?php

namespace Database\Seeders;

use App\Models\Trip;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TripSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        for ($i = 0; $i < 20; $i++) {
            Trip::create([
                'vehicle_id' => rand(3, 5),
                'user_id' => rand(1, 3),
                'trip_status_id' => rand(1, 4),
                'departure_time' => now(),
                'arrival_time' => now(),
                'departure_location' => Factory::create()->address,
                'arrival_location' => Factory::create()->address,
            ]);
        }
    }
}
