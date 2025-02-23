<?php

namespace Database\Seeders;

use App\Models\VehicleMaintenance;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleMaintenanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        for ($i = 0; $i < 50; $i++) {
            VehicleMaintenance::create([
                'vehicle_id' => rand(1, 100),
                'date' => Factory::create()->dateTimeBetween('-1 years', '+1 years'),
                'details' => Factory::create()->sentence,
                'cost' => Factory::create()->randomFloat(2, 100, 1000),
            ]);
        }
    }
}
