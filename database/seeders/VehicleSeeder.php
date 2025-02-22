<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        for ($i = 0; $i < 30; $i++) {
            $random2letter = chr(rand(65, 90)) . chr(rand(65, 90));
            $random4digit = rand(1000, 9999);
            $random2letter2 = chr(rand(65, 90)) . chr(rand(65, 90));
            Vehicle::create([
                'plate_number' => $random2letter . ' ' . $random4digit . ' ' . $random2letter2,
                'vehicle_type_id' => rand(1, 6),
                'vehicle_status_id' => rand(1, 3),
                'brand' => Factory::create()->company,
                'model' => Factory::create()->word,
                'capacity_ton' => rand(60, 100),
                'reservice_level' => rand(60, 200),
            ]);
        }
    }
}
