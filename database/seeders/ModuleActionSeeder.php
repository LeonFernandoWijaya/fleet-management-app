<?php

namespace Database\Seeders;

use App\Models\ModuleAction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModuleActionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // dashboard
        ModuleAction::create([
            'module_id' => 1,
            'action_id' => 2,
        ]);

        // trip
        ModuleAction::create([
            'module_id' => 2,
            'action_id' => 1,
        ]);

        ModuleAction::create([
            'module_id' => 2,
            'action_id' => 2,
        ]);

        ModuleAction::create([
            'module_id' => 2,
            'action_id' => 3,
        ]);

        ModuleAction::create([
            'module_id' => 2,
            'action_id' => 4,
        ]);

        //track
        ModuleAction::create([
            'module_id' => 3,
            'action_id' => 1,
        ]);

        ModuleAction::create([
            'module_id' => 3,
            'action_id' => 2,
        ]);

        //Vehicle
        ModuleAction::create([
            'module_id' => 4,
            'action_id' => 1,
        ]);

        ModuleAction::create([
            'module_id' => 4,
            'action_id' => 2,
        ]);

        ModuleAction::create([
            'module_id' => 4,
            'action_id' => 3,
        ]);

        ModuleAction::create([
            'module_id' => 4,
            'action_id' => 4,
        ]);

        //Vehicle Report
        ModuleAction::create([
            'module_id' => 5,
            'action_id' => 2,
        ]);

        ModuleAction::create([
            'module_id' => 5,
            'action_id' => 3,
        ]);

        //maintenance
        ModuleAction::create([
            'module_id' => 6,
            'action_id' => 1,
        ]);

        ModuleAction::create([
            'module_id' => 6,
            'action_id' => 2,
        ]);

        ModuleAction::create([
            'module_id' => 6,
            'action_id' => 3,
        ]);

        ModuleAction::create([
            'module_id' => 6,
            'action_id' => 4,
        ]);

        // sparepart
        ModuleAction::create([
            'module_id' => 7,
            'action_id' => 1,
        ]);

        ModuleAction::create([
            'module_id' => 7,
            'action_id' => 2,
        ]);

        ModuleAction::create([
            'module_id' => 7,
            'action_id' => 3,
        ]);

        ModuleAction::create([
            'module_id' => 7,
            'action_id' => 4,
        ]);

        // supplier

        ModuleAction::create([
            'module_id' => 8,
            'action_id' => 1,
        ]);

        ModuleAction::create([
            'module_id' => 8,
            'action_id' => 2,
        ]);

        ModuleAction::create([
            'module_id' => 8,
            'action_id' => 3,
        ]);

        ModuleAction::create([
            'module_id' => 8,
            'action_id' => 4,
        ]);

        //document
        ModuleAction::create([
            'module_id' => 9,
            'action_id' => 1,
        ]);

        ModuleAction::create([
            'module_id' => 9,
            'action_id' => 2,
        ]);

        ModuleAction::create([
            'module_id' => 9,
            'action_id' => 3,
        ]);

        ModuleAction::create([
            'module_id' => 9,
            'action_id' => 4,
        ]);

        //user

        ModuleAction::create([
            'module_id' => 10,
            'action_id' => 1,
        ]);

        ModuleAction::create([
            'module_id' => 10,
            'action_id' => 2,
        ]);

        ModuleAction::create([
            'module_id' => 10,
            'action_id' => 3,
        ]);

        ModuleAction::create([
            'module_id' => 10,
            'action_id' => 4,
        ]);

        //role

        ModuleAction::create([
            'module_id' => 11,
            'action_id' => 1,
        ]);

        ModuleAction::create([
            'module_id' => 11,
            'action_id' => 2,
        ]);

        ModuleAction::create([
            'module_id' => 11,
            'action_id' => 3,
        ]);

        ModuleAction::create([
            'module_id' => 11,
            'action_id' => 4,
        ]);
    }
}
