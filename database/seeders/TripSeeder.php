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
        for ($i = 0; $i < 40; $i++) {
            Trip::create([
                'vehicle_id' => rand(1, 100),
                'user_id' => rand(2, 31),
                'trip_status_id' => 4,
                'departure_time' => now(),
                'arrival_time' => now(),
                'actual_departure_time' => now(),
                'actual_arrival_time' => now(),
                'departure_location' => Factory::create()->address,
                'arrival_location' => Factory::create()->address,
                'trip_issue' =>  Factory::create()->sentence,
                'departure_latitude' => "-6.173544110048443",
                'departure_longitude' => "106.7689022511833",
                'arrival_latitude' => "-6.173544110048443",
                'arrival_longitude' => "106.7689022511833",
                'latest_latitude' => "-6.173544110048443",
                'latest_longitude' => "106.7689022511833",
            ]);
        }
    }
}
