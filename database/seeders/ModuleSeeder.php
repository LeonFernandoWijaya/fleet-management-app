<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Module::create(['name' => 'Dashboard']);
        Module::create(['name' => 'Trip']);
        Module::create(['name' => 'Track']);
        Module::create(['name' => 'Vehicle']);
        Module::create(['name' => 'Vehicle Report']);
        Module::create(['name' => 'Maintenance']);
        Module::create(['name' => 'Sparepart']);
        Module::create(['name' => 'Supplier']);
        Module::create(['name' => 'Document']);
        Module::create(['name' => 'User']);
        Module::create(['name' => 'Role']);
    }
}
