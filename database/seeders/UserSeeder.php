<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insert 3 global admins
        for ($id = 1; $id <= 3; $id++)
        {
            DB::table('users')->insert([
                'country_id' => rand(1, 195), // Random country ID
                'role_id' => 1, // 1 is the ID for 'global admin' role
                'name' => 'GlobalAdmin' . $id,
                'email' => 'globaladmin' . $id . '@gmail.com',
                'password' => bcrypt('GlobalAdmin' . $id),
                'is_banned' => false,
                'created_at' => now(),
                'updated_at' => NULL,
            ]);
        }

        // Insert 3 continent admins for each country
        for ($countryId = 1; $countryId <= 195; $countryId++)
        {
            for ($id = 1; $id <= 3; $id++)
            {
                $adminId = ($countryId - 1) * 3 + $id; // Unique ID for each admin

                DB::table('users')->insert([
                    'country_id' => rand(1, 195), // Assuming you have 195 countries
                    'role_id' => 2, // 2 is the ID for 'continent admin' role
                    'name' => 'ContinentAdmin' . $adminId,
                    'email' => 'continentadmin' . $adminId . '@gmail.com',
                    'password' => bcrypt('ContinentAdmin' . $adminId),
                    'is_banned' => false,
                    'created_at' => now(),
                    'updated_at' => NULL,
                ]);
            }
        }

        // Insert 100 users
        for ($id = 1; $id <= 100; $id++)
        {
            DB::table('users')->insert([
                'country_id' => rand(1, 195), // Assuming you have 10
                'role_id' => 3, // 3 is the ID for 'user' role
                'name' => 'User' . $id,
                'email' => 'user' . $id . '@gmail.com',
                'password' => bcrypt('User' . $id),
                'is_banned' => false,
                'created_at' => now(),
                'updated_at' => NULL,

            ]);
        }
    }
}
