<?php

namespace Database\Seeders;

use App\Models\Trip;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            VehicleTypeSeeder::class,
            VehicleStatusSeeder::class,
            TripStatusSeeder::class,
            ModuleSeeder::class,
            ActionSeeder::class,
            ModuleActionSeeder::class,
            RoleSeeder::class,
            RolePermissionSeeder::class,
            UserSeeder::class,
            SupplierSeeder::class,
            SparepartSeeder::class,
            VehicleSeeder::class,
            VehicleDocumentSeeder::class,
            VehicleMaintenanceSeeder::class,
            VehicleReportSeeder::class,
            TripSeeder::class,
        ]);
    }
}
