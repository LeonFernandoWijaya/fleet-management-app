<?php

namespace Database\Seeders;

use App\Models\Sparepart;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SparepartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        for ($i = 0; $i < 100; $i++) {
            Sparepart::create([
                'supplier_id' => rand(1, 30),
                'name' => Factory::create()->name,
                'stock' => rand(1, 100),
                'reorder_level' => rand(1, 100),
            ]);
        }
    }
}
