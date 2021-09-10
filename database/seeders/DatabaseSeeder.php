<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\CountriesSeeder;
use Database\Seeders\CitiesSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CountriesSeeder::class,
            CitiesSeeder::class
        ]);
    }
}
