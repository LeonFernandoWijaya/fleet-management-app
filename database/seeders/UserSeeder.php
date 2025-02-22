<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        for ($i = 0; $i < 20; $i++) {
            User::create([
                'name' => Factory::create()->name,
                'email' => Factory::create()->email,
                'password' => bcrypt('password'),
                'role_id' => 4,
            ]);
        }
    }
}
