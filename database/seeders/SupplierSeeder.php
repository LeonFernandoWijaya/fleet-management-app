<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        for ($i = 0; $i < 30; $i++) {
            Supplier::create([
                'name' => Factory::create()->name,
                'address' => Factory::create()->address,
                'contact_number' => Factory::create()->phoneNumber,
            ]);
        }
    }
}
