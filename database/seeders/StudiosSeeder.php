<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class StudiosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('studios')->insert([
            'studio_number' => '10',
            'seat_capacity' => '100',
            'created_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('studios')->insert([
            'studio_number' => '11',
            'seat_capacity' => '100',
            'created_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('studios')->insert([
            'studio_number' => '12',
            'seat_capacity' => '100',
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}
