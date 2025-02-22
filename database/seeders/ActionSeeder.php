<?php

namespace Database\Seeders;

use App\Models\Action;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Action::create(['name' => 'Create']);
        Action::create(['name' => 'Read']);
        Action::create(['name' => 'Update']);
        Action::create(['name' => 'Delete']);
    }
}
