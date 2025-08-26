<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ContinentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // Insert continents into the continents table
        $continents = [
            ['continent_id' => 1, 'name' => 'Africa'],
            ['continent_id' => 2, 'name' => 'Asia'],
            ['continent_id' => 3, 'name' => 'Europe'],
            ['continent_id' => 4, 'name' => 'North America'],
            ['continent_id' => 5, 'name' => 'South America'],
            ['continent_id' => 6, 'name' => 'Oceania'],
            ['continent_id' => 7, 'name' => 'Antarctica'],
        ];

        

        foreach ($continents as $continent) {
            DB::table('continents')->insert($continent);
        }
    }
}
