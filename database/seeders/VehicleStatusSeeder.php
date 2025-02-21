<?php

namespace Database\Seeders;

use App\Models\VehicleStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        VehicleStatus::create(['name' => 'Available']);
        VehicleStatus::create(['name' => 'In Use']);
        VehicleStatus::create(['name' => 'On Service']);
    }
}
