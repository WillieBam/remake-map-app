<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Define the roles to be seeded
        $roles = [
            ['role_id' => 1, 'name' => 'Global Admin'],
            ['role_id' => 2, 'name' => 'Continent Admin'],
            ['role_id' => 3, 'name' => 'User']
        ];

        // Insert roles into the database
        foreach ($roles as $role) {
            DB::table('roles')->insert($role);
        }
    }
}
