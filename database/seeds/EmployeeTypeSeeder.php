<?php

use App\EmployeeCategory;
use Illuminate\Database\Seeder;

class EmployeeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EmployeeCategory::create(['category' => 'supervisor']);
        EmployeeCategory::create(['category' => 'casual']);
    }
}
