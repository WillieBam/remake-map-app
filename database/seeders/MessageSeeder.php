<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class MessageSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i < 101; $i++){
            $randomTimestamp = now()->subDays(rand(0, 365))->subMinutes(rand(0, 1440));
            
            DB::table('messages')->insert([
                'user_id' => $i,
                'country_id'=> rand(1, 195),
                'content'=> Str::random(100),
                'views'=> rand(1, 1000),
                'created_at' => $randomTimestamp
            ]);
        }
    }
}
