<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\support\Facades\DB;
use Illuminate\support\Str;


class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i<100;$i++){
            DB::table('News')->insert([
                'title' => Str::random(50),
                'user_id' => rand(1,100),
                'country_id' => rand(1,195),
                'content' => Str::random(200),
                'views' => rand(0, 1000)
            ]);
        }
    }
}
