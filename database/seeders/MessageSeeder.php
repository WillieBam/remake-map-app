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
       
    for ($i = 1; $i<101; $i++){
        DB::table('messages')->insert([
            'user_id' =>$i,
            'country_id'=>random_int(1, 195),
            'content'=>$i,
            'views'=>$i,
        ]);
    }
    }
}
