<?php

namespace Database\Seeders;

use App\Models\VehicleDocument;
use Faker\Factory;
use Illuminate\Database\Seeder;

class VehicleDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        for ($i = 0; $i < 100; $i++) {
            VehicleDocument::create([
                'vehicle_id' => rand(1, 100),
                'name' => Factory::create()->word,
                'path' => '20250221_125542_Pub_20241_825210088.pdf',
                'expiry_date' => Factory::create()->dateTimeBetween('-1 years', '+1 years'),
            ]);
        }
    }
}
