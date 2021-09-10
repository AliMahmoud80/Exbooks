<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Seed with supported cities.
        DB::table('cities')->insert([
            [
                'id' => 1,
                'name' => 'Cairo',
                'country_id' => 1
            ],
            [
                'id' => 2,
                'name' => 'Alexandria',
                'country_id' => 1
            ],
        ]);
    }
}
