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
        // Create a super admin user
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'role_id' => 1,
        ]);
        //
        for ($i = 0; $i < 30; $i++) {
            User::create([
                'name' => Factory::create()->name,
                'email' => Factory::create()->email,
                'password' => bcrypt('password'),
                'role_id' => 4,
            ]);
        }

        for ($i = 0; $i < 5; $i++) {
            User::create([
                'name' => Factory::create()->name,
                'email' => Factory::create()->email,
                'password' => bcrypt('password'),
                'role_id' => rand(2, 3),
            ]);
        }
    }
}
