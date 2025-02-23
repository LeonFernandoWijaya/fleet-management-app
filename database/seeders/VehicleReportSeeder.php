<?php

namespace Database\Seeders;

use App\Models\VehicleReport;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        for ($i = 0; $i < 50; $i++) {
            VehicleReport::create([
                'vehicle_id' => rand(1, 100),
                'user_id' => rand(2, 31),
                'description' => Factory::create()->sentence,
                'is_fixed' => rand(0, 1),
            ]);
        }
    }
}
