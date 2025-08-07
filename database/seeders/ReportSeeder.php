<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
Use Illuminate\Support\Facades\DB;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 0;$i<100;$i++){
            DB::table('reports')->insert([
                'report_id' => $i,
                'user_id' => rand(0,50),
                'message_id' => rand(0,1000),
            ]);
        }
    }
}
