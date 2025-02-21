<?php

namespace Database\Seeders;

use App\Models\VehicleType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        VehicleType::create(['name' => 'Dump Truck']);
        VehicleType::create(['name' => 'Haul Truck']);
        VehicleType::create(['name' => 'Tronton']);
        VehicleType::create(['name' => 'Trailer']);
        VehicleType::create(['name' => 'Conveyor Belt Truck']);
        VehicleType::create(['name' => 'Road Train']);
    }
}
