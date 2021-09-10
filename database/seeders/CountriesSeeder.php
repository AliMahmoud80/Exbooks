<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Seed with supported countries.
        DB::table('countries')->insert(
            [
                'id' => 1,
                'name' => 'Egypt'
            ]
        );
    }
}
