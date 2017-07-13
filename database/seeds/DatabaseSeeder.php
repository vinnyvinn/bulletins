<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $this->call(PermissionsTableSeeder::class);
         $this->call(UsersTableSeeder::class);
         $this->call(PassportSeeder::class);
         $this->call(IntegrationsSeeder::class);
//         $this->call(DevelopmentSeeder::class);
    }
}
