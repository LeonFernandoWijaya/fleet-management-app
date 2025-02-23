<?php

namespace Database\Seeders;

use App\Models\ModuleAction;
use App\Models\RolePermission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $moduleActions = ModuleAction::all();

        for ($roleId = 1; $roleId <= 4; $roleId++) {
            foreach ($moduleActions as $moduleAction) {
                RolePermission::create([
                    'role_id' => $roleId,
                    'module_action_id' => $moduleAction->id,
                    'is_active' => true,
                ]);
            }
        }
    }
}
