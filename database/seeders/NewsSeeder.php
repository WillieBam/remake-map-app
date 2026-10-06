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
        for ($i = 0; $i < 100; $i++){
            $randomTimestamp = now()->subDays(rand(0, 365))->subMinutes(rand(0, 1440));

            DB::table('news')->insert([
                'title' => Str::random(50),
                'user_id' => rand(1, 500),
                'country_id' => rand(1, 195),
                'content' => Str::random(200),
                'views' => rand(0, 1000),
                'created_at' => $randomTimestamp
            ]);
        }
    }
}
