<?php

use Illuminate\Database\Seeder;
use SmoDav\Models\Station;

class DevelopmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        \factory(Station::class, 10)->create();
    }
}
