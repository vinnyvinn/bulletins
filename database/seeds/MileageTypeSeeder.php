<?php

use Illuminate\Database\Seeder;
use SmoDav\Models\MileageType;

class MileageTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MileageType::create(['name' => 'Fixed Mileage']);
        MileageType::create(['name' => 'Return Mileage']);
    }
}
